      <div class="wrap">
           <!--  <h2>Settings</h2> -->
            <h2 class="nav-tab-wrapper">
                <a href="?page=genealogical-tree" class="nav-tab <?php echo $active_tab == '' ? 'nav-tab-active' : ''; ?>"><?php _e('General Settings', 'genealogical-tree'); ?>  </a>
                <a href="?page=genealogical-tree&tab=tree-setting" class="nav-tab <?php echo $active_tab == 'tree-setting' ? 'nav-tab-active' : ''; ?>"><?php _e('Tree Settings', 'genealogical-tree'); ?>  </a>
            </h2>

            <?php if($active_tab==='') {  ?>
            <?php
                if (gt_fs()->is_not_paying() && !gt_fs()->is_trial()) {
                    echo '<section><h1>' . __('You are using Free plan, To get premium features', 'genealogical-tree') . '</h1>';
                    echo '<a class="button button-primary" href="' . gt_fs()->get_upgrade_url() . '">' .
                        __('Upgrade Now!', 'genealogical-tree') .
                        '</a>';
                    echo '
                </section>';
                }
            ?>

            <table class="form-table">
                <tr>
                    <th>
                       <?php _e('Enable Import (.ged)', 'genealogical-tree'); ?>
                        <p class="description"><i><?php _e('It will allow you to import members from a ged formated file.', 'genealogical-tree'); ?></i></p>
                    </th>

                    <td>
                        <input type="checkbox" name="gt[enable_import]" disabled="disabled"> 
                    </td>
                </tr>

                <tr>
                    <th>
                        <?php _e('Enable Export (.ged)', 'genealogical-tree'); ?>
                        <p class="description"><i><?php _e('It will allow you to export members and family to a ged formated file.', 'genealogical-tree'); ?></i></p>
                    </th>
                    <td>
                        <input type="checkbox"  name="gt[enable_import]" disabled="disabled"> 
                        
                    </td> 
                </tr>
                <tr>
                    <th>
                        <?php _e('Hide Credit Text', 'genealogical-tree'); ?>
                        <p class="description"><i><?php _e('This will Credit Text', 'genealogical-tree'); ?></i></p>
                    </th>
                    <td>
                        <input type="checkbox" name="gt[enable_import]"  disabled="disabled"> 
                    </td> 
                </tr>


            </table>


            <?php } ?>


            <?php if($active_tab==='tree-setting') {  ?>

                <ul class="gt-subnav">
                    <li>
                        <a href="?page=genealogical-tree&tab=tree-setting&section=tree-style-1" class="<?php echo $active_section == 'tree-style-1' ? 'gt-subnav-active' : ''; ?>"><?php _e('Tree Style 1', 'genealogical-tree'); ?>  </a> | 
                    </li>
                    <li>
                        <a href="?page=genealogical-tree&tab=tree-setting&section=tree-style-2" class="<?php echo $active_section == 'tree-style-2' ? 'gt-subnav-active' : ''; ?>"><?php _e('Tree Style 2', 'genealogical-tree'); ?>  </a> 
                    </li>
                </ul>

                <?php if($active_section == 'tree-style-1') { ?>
                    <table class="form-table">
                        <tr>
                            <th colspan="2">
                                Layout Setting
                            </th>
                        </tr>
                        <tr>
                            <th>
                                Display Name: 
                            </th>
                            <td>
                                <input type="radio" name=""> Full Name
                                <input type="radio" name=""> Given Name
                                <input type="radio" name=""> Surname
                            </td>
                        </tr>

                        <tr>
                            <th>
                                Show Gender: 
                            </th>
                            <td>
                                <input type="checkbox" name=""> 
                            </td> 
                        </tr>

                        <tr>
                            <th>
                                Show Living date: 
                            </th>
                            <td>
                                <input type="checkbox" name=""> 
                            </td> 
                        </tr>

                        <tr>
                            <th>
                               Show Living dates for those alive: 
                            </th>
                            <td>
                                <input type="checkbox" name=""> 
                            </td> 
                        </tr>

                        <tr>
                            <th>
                                Show only one spouse:
                            </th>
                            <td>
                                <input type="checkbox" name="">
                            </td> 
                        </tr>

                        <tr>
                            <th>
                                Show Father and Mother of Selected root (If avalable)
                            </th>
                            <td>
                                <input type="checkbox" name="">
                            </td> 
                        </tr>

                        <tr>
                            <th>
                                Show Father and Mother of Selected root (If Avalable)
                            </th>
                            <td>
                                <input type="checkbox" name="">
                            </td> 
                        </tr>

                        <tr>
                            <th>
                                Show Top Button for Tree Link of Father and Mother of Selected root (If Avalable)
                            </th>
                            <td>
                                <input type="checkbox" name="">
                            </td> 
                        </tr>


                        <tr>
                            <th colspan="2">
                                Appearance Setting
                            </th>
                        </tr>
                    </table>
                <?php } ?>

                <?php if($active_section == 'tree-style-2') { ?>
                    Comming Soon
                <?php } ?>


            <?php } ?>
        </div>