<?php  if(isset($breadcrumb) && count($breadcrumb) > 0):   ?>
<div class="page-header">
    <div class="page-header-content">
        <ul class="breadcrumb position-right" style="padding: 20px 0px 20px">
            <?php foreach ($breadcrumb as $key => $value): ?> 
                <?php if(isset($value['active'])): ?>
                    <li class="active">{{$value['title']}}</li>
                <?php else: ?>
                    <li><a href="{{$value['url']}}">{{$value['title']}}</a></li> 
                <?php endif; ?> 
            <?php endforeach; ?>
        </ul>
    </div>
</div>
<?php endif; ?>