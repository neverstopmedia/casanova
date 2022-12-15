<?php $timeline = $args['timeline']; ?>

<div class="cc-timeline cc-timeline_casino">
    <?php 
    $timeline   = array_reverse($timeline);
    $diff       = sizeof( $timeline ) - 5;

    foreach( array_slice($timeline, 0, 5, true) as $key => $item ){ 
    $item = $item['data'];
    $data = json_decode($item, true);
    $date = date_create($data['date']);

    ?>
    <div class="cc-timeline_item <?php echo $key == 0 ? 'current' : null ?>">
        <p class="cc-timeline_item_time"><?php echo date_format($date, "l F d, Y H:i:s") ?> by <i><?php echo $data['user'] ?></i></p>
        <?php echo $key == 0 ? '<span class="badge">Latest Update</span>' : null ?>
        <div class="cc-timeline_item_block cc-timeline_item_affiliates">
            <?php foreach( $data['data'] as $time ){ ?>
            <div>
                <span class="cc-timeline_item_site_id"><?php echo get_the_title($time['affiliate_site_id']); ?></span>
                <span class="cc-timeline_item_aff_link"><?php echo $time['affiliate_link']; ?></span>
            </div>
            <?php } ?>
        </div>
    </div>
    <?php } ?>
</div>
<?php echo sizeof( $timeline ) > 5 ? '<div class="cc-timeline_old">There are '. $diff  . ' older updates</div>' : null ?>