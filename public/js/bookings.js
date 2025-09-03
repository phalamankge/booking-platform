const form = document.getElementById('bookingForm');
const tableBody = document.querySelector('#bookingsTable tbody');
const msgBox = document.getElementById('message');
const token = document.querySelector('meta[name="csrf-token"]').content;

form.addEventListener('submit', async (e) => {
    e.preventDefault();
    msgBox.textContent = '';

    const data = Object.fromEntries(new FormData(form));

    const res = await fetch('/api/bookings', {
        method: 'POST',
        headers: {'Content-Type':'application/json','X-CSRF-TOKEN':token},
        body: JSON.stringify(data)
    });

    const json = await res.json();
    if (res.ok) {
        msgBox.className = 'success';
        msgBox.textContent = 'Booking created!';
        form.reset();
        fetchBookings();
    } else {
        msgBox.className = 'error';
        msgBox.textContent = json.error ?? 'Error creating booking';
    }
});

async function fetchBookings() {
    const weekDate = document.getElementById('weekPicker').value;
    const userId   = document.getElementById('userFilter').value;
    const clientId = document.getElementById('clientFilter').value;

    let url = `/api/bookings?week=${weekDate}`;
    if (userId) url += `&user_id=${userId}`;
    if (clientId) url += `&client_id=${clientId}`;

    const res = await fetch(url);
    const bookings = await res.json();

    tableBody.innerHTML = '';
    bookings.forEach(b => {
        const row = `<tr>
            <td>${escapeHtml(b.title)}</td>
            <td>${escapeHtml(b.user.name)}</td>
            <td>${escapeHtml(b.client.name)}</td>
            <td>${formatDate(b.start_time)}</td>
            <td>${formatDate(b.end_time)}</td>
        </tr>`;
        tableBody.innerHTML += row;
    });
}

function formatDate(dateStr) {
    const d = new Date(dateStr);
    return isNaN(d) ? dateStr : d.toLocaleString();
}

function escapeHtml(str) {
    return String(str || '')
        .replace(/&/g,'&amp;')
        .replace(/</g,'&lt;')
        .replace(/>/g,'&gt;')
        .replace(/"/g,'&quot;')
        .replace(/'/g,'&#39;');
}

// initial load
fetchBookings();
