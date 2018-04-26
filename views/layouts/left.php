<aside class="main-sidebar">

    <section class="sidebar">

        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="<?= $directoryAsset ?>/img/user2-160x160.jpg" class="img-circle" alt="User Image"/>
            </div>
            <div class="pull-left info">
                <p><?= Yii::$app->user->identity->username ?></p>
                <a href="#"><i class="fa fa-circle text-info"></i> 超级管理员</a>
            </div>
        </div>

        <!-- search form -->
        <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Search..."/>
              <span class="input-group-btn">
                <button type='submit' name='search' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
            </div>
        </form>
        <!-- /.search form -->

		 <?php 
		    use yii\bootstrap\Nay;
		    use yiiplus\desktop\components\MenuHelper; 
		    $callback = function($menu){ 
		        $data = json_decode($menu['data'], true); 
		        $items = $menu['children']; 
		        $return = [ 
		            'label' => $menu['name'], 
		            'url' => [$menu['route']], 
		        ]; 
		        //处理我们的配置 
		        if ($data) { 
		            //visible 
		            isset($data['visible']) && $return['visible'] = $data['visible']; 
		            //icon 
		            isset($data['icon']) && $data['icon'] && $return['icon'] = $data['icon']; 
		            //other attribute e.g. class... 
		            $return['options'] = $data; 
		        } 
		        //没配置图标的显示默认图标 
		        (!isset($return['icon']) || !$return['icon']) && $return['icon'] = 'circle'; 
		        $items && $return['items'] = $items; 
		        return $return; 
		    }; 
		    //这里我们对一开始写的菜单menu进行了优化
		    echo dmstr\widgets\Menu::widget( [ 
		        'options' => ['class' => 'sidebar-menu tree', 'data-widget'=> 'tree'],
		        'items' => MenuHelper::getAssignedMenu(Yii::$app->user->id, null, $callback), 
		    ] ); 
		?>

    </section>

</aside>
