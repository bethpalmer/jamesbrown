    <?php
    $filter = '';
    $s = '';
    if (isset($_GET, $_GET['filter'])) {
        $filter = $_GET['filter'];
    }

    if (isset($_GET, $_GET['s'])) {
        $s = trim($_GET['s']);
    }

    ?>

    <?php if (isset($notice_message)) : ?>
        <div class="notice notice-<?php echo $notice_type; ?> is-dismissible">
            <p><?php echo $notice_message; ?></p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>
        </div>

    <?php endif?>
<div id="wrapper" class="pure-container">
    <div class="pure-g" id='links-nav-container' >
        <div class="pure-u-1">
            <ul id='navigation-links'>
                <li>
                    <a target="_blank" href="<?php echo $this->container['admin_dashboard']->myAccountLink() ?>"><?php echo $this->container['provider'] ?> Account</a>
                </li>
                <li>
                    <a href="<?php echo $this->container['admin_dashboard']->refreshAccountLink() ?>">Refresh Account</a>
                </li>
                <li>
                    <a href="<?php echo $this->container['dashboard_page'] . '&action=logout' ?>" class="logout-link">Logout</a>
                </li>
            </ul>
        </div>
    </div>
    <div class="pure-g">
        <div class="pure-u-1" id='account-heading'>
            <h1>
                <img src="<?php echo $grav_url ?>" alt="<?php echo $data['name'] ?>">
                <span><?php echo $data['name'] ?></span>
            </h1>
            <div>
                <b>Active Memberships:</b>
                <ul id='membership-list'>
                    <?php foreach ($data['memberships'] as $name) : ?>
                        <li><span><?php echo $name; ?></span></li>
                    <?php endforeach;?>
                </ul>

            </div>
        <div>
            <form method="GET">
                <p class="search-box">
                <input type="hidden" name="page" value="diviframework-hub">
                <select name="filter">
                    <option value=''>Any</option>
                    <?php foreach ($this->container['admin_dashboard']->getUniqueTypes($data['extensions']) as $type) : ?>
                        <option value="<?php echo $type; ?>" <?php echo ($filter == $type) ? 'selected' : ''; ?>><?php echo $type; ?></option>
                    <?php endforeach; ?>
                </select>
                <input type="text" name="s" value="<?php echo $s; ?>" style="width:500px;">
                <button type="submit" class="button button-primary">Search</button>
                <a class="button" href="<?php echo admin_url('/admin.php?page=diviframework-hub'); ?>">Clear</a>
                </p>
            </form>
            
        </div>

        </div>
    </div>
    <hr>
    <?php foreach ($this->container['admin_dashboard']->filteredExtensions($data['extensions']) as $extension) : ?>
        <div class="pure-g" id='extension-container'>
            <div class="pure-u-3-24">
                <img src="<?php echo $extension['icon'] ?>" alt="<?php echo $extension['name'] ?>">
            </div>

            <div class="pure-u-18-24">
                <h3>
                    <span><?php echo $extension['name'] ?></span>
                    <div class="badge">
                        <div class="status green">
                            <span>
                                <a href="<?php echo $this->container['dashboard_page'] . '&filter=' . $extension['type'] ?>"><?php echo $extension['type'] ?></a>
                            </span>
                        </div>
                    </div>
                </h3>


                <p><?php echo $extension['description'] ?></p>
            </div>

            <div class="pure-u-3-24 t-center" id='cta-container'>
                <?php $status = $this->container['user_account']->extensionStatus($extension);?>

                <?php if ($status == 'plugin-installed-activated') : ?>
                    <img src="<?php echo $this->container['plugin_url'] . '/resources/images/checkmark.png' ?>" alt="">
                    <div class='t-center'>Installed & Activated</div>
                <?php endif;?>

                <?php if ($status == 'plugin-installed') : ?>
                    <div class='t-center'>
                        <a href="<?php echo $this->container['admin_dashboard']->pluginActivationLink($extension['plugin_path']) ?>" class="pure-button" target='_blank'>Activate</a>
                    </div>
                <?php endif;?>


                <?php if ($status == 'plugin-update-available') : ?>
                    <div class='t-center'>
                        <a href="<?php echo $this->container['admin_dashboard']->upgradePluginLink($extension) ?>" class="pure-button" onclick='return window.confirm("Please confirm to begin the upgrade.")'>Upgrade Now</a>
                    </div>
                <?php endif;?>

                <?php if ($status == 'plugin-absent') : ?>
                    <div class='t-center'>
                        <a href="<?php echo $this->container['admin_dashboard']->installPluginLink($extension) ?>" class="pure-button bg-green" onclick='return window.confirm("Please confirm to begin the install.")'>Install</a>
                    </div>
                <?php endif; ?>
                    <div class='t-center mt-10'>
                        <a href="<?php echo $this->container['admin_dashboard']->downloadPluginLink($extension['slug']) ?>" class="">Download</a>
                    </div>

            </div>
        </div>
        <hr>
    <?php endforeach;?>

</div>