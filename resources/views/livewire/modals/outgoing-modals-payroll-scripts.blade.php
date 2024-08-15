@script
<script>
    VirtualSelect.init({
        ele: '#outgoing_payroll_type_select',
        options: [{
            label: 'J.O',
            value: 'job order'
        }, {
            label: 'Regular',
            value: 'regular'
        }],
        maxWidth: '100%',
        zIndex: 10,
        popupDropboxBreakpoint: '3000px',
    });

    let payroll_type = document.querySelector('#outgoing_payroll_type_select');
    payroll_type.addEventListener('change', () => {
        let data = payroll_type.value;
        @this.set('payroll_type', data);
    });
</script>
@endscript