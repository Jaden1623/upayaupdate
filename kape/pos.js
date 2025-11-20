document.addEventListener('DOMContentLoaded', () => {
  const productGrid = document.getElementById('product-grid');
  const orderSummaryBox = document.getElementById('order-summary-box');
  const hiddenOrderContainer = document.getElementById('hidden-order-inputs');
  const checkoutForm = document.getElementById('checkout-form');
  const clearBtn = document.querySelector('.clear');
  const voidBtn = document.querySelector('.void');

  // Initialize orderItems from PHP session data passed as initialOrder
  const orderItems = initialOrder ? JSON.parse(JSON.stringify(initialOrder)) : [];
  let selectedIndex = -1;

  // Function to synchronize orderItems with PHP session via AJAX
  function updateSession() {
    fetch(window.location.pathname, {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: 'update_order=1&order=' + encodeURIComponent(JSON.stringify(orderItems)),
    });
  }

  // Render the order summary on the right panel
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

  // Add product to order on grid click
  productGrid.addEventListener('click', (e) => {
    const target = e.target.closest('.item');
    if (!target) return;

    const nameText = target.textContent.trim();
    const splitIndex = nameText.lastIndexOf(' - ');
    if (splitIndex === -1) return;
    
    const name = nameText.substring(0, splitIndex);
    const price = Number(nameText.substring(splitIndex + 3));
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

  // Clear all orders button
  clearBtn.addEventListener('click', () => {
    orderItems.length = 0;
    selectedIndex = -1;
    renderOrderSummary();
    updateSession();
  });

  // Void (remove) selected order item
  voidBtn.addEventListener('click', () => {
    if (selectedIndex === -1) {
      alert('Please select an item to void!');
      return;
    }
    orderItems.splice(selectedIndex, 1);
    selectedIndex = -1;
    renderOrderSummary();
    updateSession();
  });

  // On checkout form submit, add hidden inputs for order items
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

  // Initial rendering
  renderOrderSummary();
});
