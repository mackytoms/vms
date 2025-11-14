<script>

$('#visitorTable').DataTable({
    processing: true,
    serverSide: true,
    ajax: {
        url: '<?= base_url("api/visitors/datatable") ?>',
        type: 'POST'
    },
    columns: [
        {data: 'id'},
        {data: 'visitor_photo', render: function(data) {
            return '<img src="' + data + '" class="visitor-photo">';
        }},
        {data: 'visitor_name'},
        {data: 'company'},
        {data: 'host_name'},
        {data: 'purpose_name'},
        {data: 'check_in_time'},
        {data: 'status', render: function(data) {
            return '<span class="status-badge ' + data + '">' + data + '</span>';
        }},
        {data: 'actions', orderable: false, searchable: false}
    ]
});

// Poll for updates every 30 seconds
setInterval(function() {
    updateDashboardStats();
    refreshActiveVisitors();
}, 30000);

function updateDashboardStats() {
    $.get('<?= base_url("api/stats/today") ?>', function(data) {
        $('#todayTotal').text(data.total_visitors);
        $('#currentlyIn').text(data.currently_in);
        $('#avgDuration').text(data.avg_duration + 'h');
    });
}

// In Admin controller constructor
public function __construct() {
    parent::__construct();
    // Add authentication check
    if(!$this->session->userdata('admin_logged_in')) {
        redirect('login');
    }
}

</script>
