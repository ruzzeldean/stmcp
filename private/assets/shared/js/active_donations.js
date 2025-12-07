$.ajax({
  url: '../../api/donations/active_donations.php',
  method: 'GET',
  dataType: 'json',
  success: (res) => {
    if (res.status === 'success') {
      activeDonations(res.data);
    } else {
      console.error(res.message);
    }
  },
  error: (error) => {
    console.log('Error fetching donations', error);
  },
});

function activeDonations(donations) {
  const $body = $('#table-body');
  $body.empty();

  donations.forEach((donation) => {
    const html = `
      <tr>
        <td class="text-truncate max-w-250">${donation.purpose}</td>
        <td>${donation.start_date}</td>
        <td>${donation.due_date}</td>
        <td><span class="badge badge-pill bg-success">${
          donation.status
        }</span></td>
        <td>${donation.creator}</td>
        <td>${donation.approver ?? ''}</td>
        <td>
          <a class="btn btn-primary"
            href="./donation_details.php?id=${donation.donation_id}">View</a>
        </td>
      </tr>
    `;

    $body.append(html);
  });
}
