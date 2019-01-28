<?php
use yii\helpers\Html;
use yii\widgets\DetailView;
use yiiplus\desktop\components\Helper;

$this->title = $model->username;
$this->params['breadcrumbs'][] = ['label' => Yii::t('yiiplus/desktop', '用户列表'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$controllerId = $this->context->uniqueId . '/';
?>

<div class="nav-tabs-custom">
    <ul class="nav nav-tabs">
      <li class="active"><a href="#base" data-toggle="tab" aria-expanded="true"><?= Yii::t('yiiplus/desktop', '基本'); ?></a></li>
      <li class=""><a href="#assignment" data-toggle="tab" aria-expanded="false"><?= Yii::t('yiiplus/desktop', '分配'); ?></a></li>
      <li class=""><a href="#logs" data-toggle="tab" aria-expanded="false"><? Yii::t('yiiplus/desktop', '日志'); ?></a></li>
    </ul>
    <div class="tab-content">
      <div class="tab-pane active" id="base">
        <?=
        DetailView::widget([
            'model' => $model,
            'attributes' => [
                'username',
                'email:email',
                'created_at:date',
                'status',
            ],
        ])
        ?>
      </div>
      <div class="tab-pane" id="assignment">
        assignment
      </div>
      <div class="tab-pane" id="logs">
        <ul class="timeline timeline-inverse">
          <!-- timeline time label -->
          <li class="time-label">
                <span class="bg-red">
                  10 Feb. 2014
                </span>
          </li>
          <!-- /.timeline-label -->
          <!-- timeline item -->
          <li>
            <i class="fa fa-envelope bg-blue"></i>

            <div class="timeline-item">
              <span class="time"><i class="fa fa-clock-o"></i> 12:05</span>

              <h3 class="timeline-header"><a href="#">Support Team</a> sent you an email</h3>

              <div class="timeline-body">
                Etsy doostang zoodles disqus groupon greplin oooj voxy zoodles,
                weebly ning heekya handango imeem plugg dopplr jibjab, movity
                jajah plickers sifteo edmodo ifttt zimbra. Babblely odeo kaboodle
                quora plaxo ideeli hulu weebly balihoo...
              </div>
              <div class="timeline-footer">
                <a class="btn btn-primary btn-xs">Read more</a>
                <a class="btn btn-danger btn-xs">Delete</a>
              </div>
            </div>
          </li>
          <!-- END timeline item -->
          <!-- timeline item -->
          <li>
            <i class="fa fa-user bg-aqua"></i>

            <div class="timeline-item">
              <span class="time"><i class="fa fa-clock-o"></i> 5 mins ago</span>

              <h3 class="timeline-header no-border"><a href="#">Sarah Young</a> accepted your friend request
              </h3>
            </div>
          </li>
          <!-- END timeline item -->
          <!-- timeline item -->
          <li>
            <i class="fa fa-comments bg-yellow"></i>

            <div class="timeline-item">
              <span class="time"><i class="fa fa-clock-o"></i> 27 mins ago</span>

              <h3 class="timeline-header"><a href="#">Jay White</a> commented on your post</h3>

              <div class="timeline-body">
                Take me to your leader!
                Switzerland is small and neutral!
                We are more like Germany, ambitious and misunderstood!
              </div>
              <div class="timeline-footer">
                <a class="btn btn-warning btn-flat btn-xs">View comment</a>
              </div>
            </div>
          </li>
          <!-- END timeline item -->
          <!-- timeline time label -->
          <li class="time-label">
                <span class="bg-green">
                  3 Jan. 2014
                </span>
          </li>
          <!-- /.timeline-label -->
          <!-- timeline item -->
          <li>
            <i class="fa fa-camera bg-purple"></i>

            <div class="timeline-item">
              <span class="time"><i class="fa fa-clock-o"></i> 2 days ago</span>

              <h3 class="timeline-header"><a href="#">Mina Lee</a> uploaded new photos</h3>

              <div class="timeline-body">
                <img src="http://placehold.it/150x100" alt="..." class="margin">
                <img src="http://placehold.it/150x100" alt="..." class="margin">
                <img src="http://placehold.it/150x100" alt="..." class="margin">
                <img src="http://placehold.it/150x100" alt="..." class="margin">
              </div>
            </div>
          </li>
          <!-- END timeline item -->
          <li>
            <i class="fa fa-clock-o bg-gray"></i>
          </li>
        </ul>
      </div>
    </div>
</div>
