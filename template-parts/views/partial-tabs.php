<?php
$page = isset($_GET['page']) ? explode("-", $_GET['page'], 2) : '';
$slug = !empty($page[0]) ? $page[0] : '';
?>

<main class="ss-main-wrapper">

  <div class="ss-inner-wrapper">

    <div id="ss-db-navigation">
        <div class="nav-tab-wrapper current">
          <a class="nav-tab <?php echo (!empty($page[1]) && $page[1] === 'dashboard') ? 'nav-tab-active' : ''; ?>" href="<?php echo esc_url( admin_url( 'admin.php?page=' . $slug . '-dashboard' ) ); ?>">
            <?php echo esc_html__('Dashboard', 'watchy') ?>
          </a>
        </div>
      </div>