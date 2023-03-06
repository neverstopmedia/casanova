<?php $timeline = $args['timeline']; ?>

<div class="d-flex">
    <div class="f-600">
        <div class="cc-timeline cc-timeline_list">
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
                <div class="cc-timeline_item_block_wrap">
                    <div class="cc-timeline_item_block cc-timeline_item_sites">
                        <b>Connected Sites (<?php echo sizeof($data['connected_sites']) ?>)</b>
                        <div>
                            <?php foreach( $data['connected_sites'] as $site ){ ?>
                            <span><?php echo get_the_title($site) ?></span>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="cc-timeline_item_block cc-timeline_item_casinos">
                        <b>Casinos (<?php echo sizeof($data['data']) ?>)</b>
                        <div>
                            <?php foreach( $data['data'] as $time ){ ?>
                            <span><?php echo get_the_title($time['list_order_item']); ?></span>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>
        <?php echo sizeof( $timeline ) > 5 ? '<div class="cc-timeline_old">There are '. $diff  . ' older updates</div>' : null ?>
    </div>
    <?php if( $connected_sites = get_field( 'connected_sites' ) ){ ?>
    <div class="ml-40 f-1">
        <p>This is a demo from one of the connected sites.</p>
        <select id="demo-site-select">
            <?php foreach( $connected_sites as $site ){ ?>
            <option value="<?php echo get_field( 'url', $site ) ?>"><?php echo get_the_title($site) ?></option>
            <?php } ?>
        </select>
        <div class="demo-preview">
            <iframe src="<?php echo get_field( 'url', $connected_sites[0] ) ?>"></iframe>
        </div>
    </div>
    <?php } ?>
</div>