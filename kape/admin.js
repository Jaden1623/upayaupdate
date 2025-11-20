document.addEventListener('DOMContentLoaded', () => {
  const productGrid = document.getElementById('product-grid');
  const orderSummaryBox = document.getElementById('order-summary-box');
  const hiddenOrderContainer = document.getElementById('hidden-order-inputs');
  const checkoutForm = document.getElementById('checkout-form');
  const clearBtn = document.querySelector('.clear');
  const voidBtn = document.querySelector('.void');

  // Use the session-stored order from PHP
  const orderItems = typeof initialOrder !== 'undefined' && initialOrder
    ? JSON.parse(JSON.stringify(initialOrder))
    : [];
  let selectedIndex = -1;

  // Sync orderItems with PHP session via AJAX
  function updateSession() {
    fetch(window.location.pathname, {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: 'update_order=1&order=' + encodeURIComponent(JSON.stringify(orderItems)),
    });
  }

  // Render the full order summary (all items from session)
  function renderOrderSummary() {
    if (orderItems.length === 0) {
      orderSummaryBox.innerHTML = '<p>No items added yet.</p>';
      selectedIndex = -1;
      return;
    }

    const ul = document.createElement('ul');
    ul.style.listStyle = 'none';
    ul.style.padding = 0;

    orderItems.forEach((item, index) => {
      const li = document.createElement('li');
      li.textContent = `${item.name} – ₱${item.price} x ${item.qty}`;
      li.style.marginBottom = '8px';
      li.style.cursor = 'pointer';
      li.style.padding = '4px 8px';
      li.style.borderRadius = '4px';
      if (index === selectedIndex) {
        li.style.backgroundColor = '#add8e6';
      }

      li.addEventListener('click', () => {
        selectedIndex = selectedIndex === index ? -1 : index;
        renderOrderSummary();
      });

      ul.appendChild(li);
    });

    orderSummaryBox.innerHTML = '';
    orderSummaryBox.appendChild(ul);
  }

  // Add product to order (works for any POS page)
  if (productGrid) {
    productGrid.addEventListener('click', (e) => {
      const target = e.target.closest('.item');
      if (!target) return;

      const name = target.getAttribute('data-name');
      const price = Number(target.getAttribute('data-price'));
      if (!name || isNaN(price)) return;

      const existingIndex = orderItems.findIndex(i => i.name === name);
      if (existingIndex !== -1) {
        orderItems[existingIndex].qty++;
      } else {
        orderItems.push({ name, price, qty: 1 });
      }

      selectedIndex = -1;
      renderOrderSummary();
      updateSession();
    });
  }

  // Clear all items
  clearBtn.addEventListener('click', () => {
    if (!confirm('Clear the entire order?')) return;
    orderItems.length = 0;
    selectedIndex = -1;
    renderOrderSummary();
    updateSession();
  });

  // Void selected item with admin password
  voidBtn.addEventListener('click', async () => {
    if (selectedIndex === -1) {
      alert('Please select an item to void!');
      return;
    }

    const adminPass = prompt('Enter Admin password to void this item:');
    if (!adminPass) return;

    try {
      const response = await fetch('void_auth.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'check_pass=' + encodeURIComponent(adminPass)
      });
      const result = await response.json();

      if (result.valid) {
        orderItems.splice(selectedIndex, 1);
        selectedIndex = -1;
        renderOrderSummary();
        updateSession();
        alert('Item voided successfully!');
      } else {
        alert('Invalid Admin password. Item not voided.');
      }
    } catch (err) {
      console.error(err);
      alert('Error verifying admin password.');
    }
  });

  // On checkout, create hidden inputs for order items
  checkoutForm.addEventListener('submit', (e) => {
    hiddenOrderContainer.innerHTML = '';
    if (orderItems.length === 0) {
      alert('Please add at least one item to your order.');
      e.preventDefault();
      return;
    }
    orderItems.forEach((item, i) => {
      ['name', 'price', 'qty'].forEach(field => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = `order[${i}][${field}]`;
        input.value = item[field];
        hiddenOrderContainer.appendChild(input);
      });
    });
  });

  // Initial render
  renderOrderSummary();
});
