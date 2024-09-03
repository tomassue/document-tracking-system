<div>
    <div class="main-panel">

        <!-- We need to pass props to the children to determine which page we're in and manipulate data to be displayed in this page (dashboard) -->

        <livewire:cpso.incoming.request :page_type="$dashboard" />
        <livewire:cpso.incoming.documents :page_type="$dashboard" />
    </div>
    <!-- main-panel ends -->
</div>