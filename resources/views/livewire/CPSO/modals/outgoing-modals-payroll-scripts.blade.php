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
        // popupDropboxBreakpoint: '3000px',
    });

    let payroll_type = document.querySelector('#outgoing_payroll_type_select');
    payroll_type.addEventListener('change', () => {
        let data = payroll_type.value;
        @this.set('payroll_type', data);
    });

    //NOTE - Edit Mode
    $wire.on('set_payroll_type_select', (key) => {
        document.querySelector('#outgoing_payroll_type_select').disable();
        document.querySelector('#outgoing_payroll_type_select').setValue(key[0]);
        // console.log(key[0]);
    });
</script>
@endscript