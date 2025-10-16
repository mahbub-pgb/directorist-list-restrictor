
jQuery(document).ready(function($){
    function updateSelectOptions() {
        // First, enable all options
        $('.status-select option').prop('disabled', false);

        // Disable options that are already selected in other selects
        $('.status-select').each(function(){
            var selectedStatus = $(this).val();
            if(selectedStatus) {
                $('.status-select').not(this).each(function(){
                    $(this).find('option[value="'+selectedStatus+'"]').prop('disabled', true);
                });
            }
        });
    }

    $('.status-select').on('change', function(){
        updateSelectOptions();
    });

    // Trigger on page load
    updateSelectOptions();
});