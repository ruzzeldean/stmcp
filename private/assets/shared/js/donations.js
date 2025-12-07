$.ajax({
  url: '../../api/donations/donations.php',
  method: 'GET',
  dataType: 'json',
  success: (res) => {
    if (res.status === 'success') {
      $('#active-count').text(res.data.total_active);
      $('#closed-count').text(res.data.total_closed);
      myDonations(res.data.my_donations);
    } else {
      console.log(res.message);
    }
  },
  error: (err) => {
    console.error('error ginger');
    console.error(err);
  },
});

function myDonations(donations) {
  const $body = $('#table-body');
  $body.empty();

  donations.forEach((donation) => {
    const badgeColor = donation.status === 'Active' ? 'success' : 'secondary';

    const html = `
      <tr>
        <td class="text-truncate max-w-250">${donation.purpose}</td>
        <td>Php ${donation.amount}</td>
        <td>${donation.start_date}</td>
        <td>${donation.due_date}</td>
        <td><span class="badge badge-pill bg-${badgeColor}">${donation.status}</span></td>
        <td>
          <a class="btn btn-primary" href="./donation_details.php?id=${donation.donation_id}">View</a>
        </td>
      </tr>
    `;

    $body.append(html);
  });
}
