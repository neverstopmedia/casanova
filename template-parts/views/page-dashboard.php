<div class="ss-title">
    <h1>Casanova</h1>
    <p>Casino sites on steroids</p>
</div>

<form id="process_applications">
    <p>This button will trigger applications to get synced</p>
    <p><b>Last Sync:</b> <?php echo get_field('last_app_domain_check', 'option') ?></p>
    <?php if( !Casanova_Casino_Helper::last_domain_check_run() ){ ?>
        <p style="color: red;"><b>Its been less than 24 hours since the last sync</b></p>
    <?php } ?>
    <button class="button" type="submit">Sync Application Domains</button>

    <div class="upload_status">
        <b>Once you press the button, the domains will attempt to sync, and results will show here</b>
    </div>

</form>