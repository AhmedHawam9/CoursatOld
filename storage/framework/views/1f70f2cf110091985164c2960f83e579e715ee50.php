
<?php $__env->startSection('content'); ?>
<style>
		.studentprofile .img img{
            border-radius: 50%;
            margin: 0 auto;
            display: flex;
            width: 70%;
        }
		.studentprofile .qr img{
            margin: 0 auto;
            display: flex;
            width: 80%;
        }
		.studentprofile .qr a{
            margin: 5px auto;
            display: flex;
        }
        .studentprofile h4{
            display: flex;
            justify-content: center;
            margin: 5% 0 0;
        }
        .studentprofile h5{
            display: flex;
            justify-content: center;
            margin: 2% 0;
        }
        .studentprofile p{
            margin-right: 2%;
            margin-left: 2%;
        }
	.studentprofile .fl{
		margin-top: 5%;
		box-shadow: 0 5px 5px 5px #d5d5d573;
    	padding: 5%;
	}
	.studentprofile .qr{
		position: absolute;
		top: 15%;
	}
	.studentprofile .img{
		position: absolute;
		top: 15%;
	}
</style>
<!--start page-body-->
        <div class="page-body">
            <div class="container">

                     <!--start heed-->
                           <div class="heed">

                    <div class="row">
                <div class="profile">
                    <div class="row">
                        <div class="col-3">
                            <img src="<?php echo e(asset('images/profile.svg')); ?>">
                        </div>
                        <div class="col-6">
                            <h5><?php echo e(auth()->user()->name); ?></h5>
                            <p>ادمن</p>

                        </div>

            
                            </div>
                        </div>
                        <div class="flag">

                            <div class="row">
                                <div class="col-4">
                                    <img src="<?php echo e(asset('images/flag.svg')); ?>">
                                </div>
                                <div class="col-4">
                                    <h5>العربية</h5>


                                </div>

                         

                            </div>

                        </div>


                        <div class="noti text-center">
                            <span><i class="far fa-bell"></i></span>
                        </div>



                        <div class="search">

                            <input type="text" name="search">
                            <span class="srch"><i class="fas fa-search"></i></span>

                        </div>

                        <div class="datee">
                            <div class="row">
                                <span><i class="far fa-calendar-alt"></i></span>
                                <p><?php echo e(Carbon\Carbon::now()->format('d-m-Y')); ?></p>
                            </div>
                        </div>


                    </div>


                </div>
                <!--end heed-->
 <?php 
		$center = \App\User::where('id',$id)->first();		
				?>

                <!--start setting-->
                <div class="col-12 studentprofile">
	<div class="row fl bg-white">
       <div class="col-3">
            <div class="img">
                <img src="<?php echo e(asset('uploads/'.$center->image)); ?>">
                <h4><?php echo e($center->name); ?></h4>
                <h5><?php echo e($center->code); ?></h5>
            </div>
        </div>
        <div class="col-6">
            <div class="row">
                <h6>الايميل:</h6>
                <p><?php echo e($center->email); ?></p>
            </div>
			<div class="row">
                <h6>التليفون:</h6>
                <p><?php echo e($center->phone); ?></p>
            </div>
            <div class="row">
                <h6>المحافظة:</h6>
				<?php if($center->state): ?>
                <p><?php echo e($center->state['state']); ?></p>
				<?php endif; ?>
            </div>
            <div class="row">
                <h6>المدينة:</h6>
                <?php if($center->city): ?>
                <p><?php echo e($center->city['city']); ?></p>
				<?php endif; ?>
            </div>
            <div class="row">
                <h6>العنوان:</h6>
                <p><?php echo e($center->address); ?> </p>
            </div>
            <div class="row">
                <h6>عدد الكورسات:</h6>
				<?php if($center->category_id == 1): ?>
                <p><?php echo e(count($center->centertypes)); ?></p>
				<?php elseif($center->category_id == 2): ?>
				<p><?php echo e(count($center->centertypescollege)); ?></p>
				<?php elseif($center->category_id == 3): ?>
					<p><?php echo e(count($center->centercourses)); ?></p>
				<?php endif; ?>
            </div>
			<div class="row">
                <h6 for="year">نوع المركز:</h6>
				<?php if($center->category): ?>   
                <p><?php echo e($center->category['name']); ?></p>
				<?php endif; ?>
            </div>
            <div class="row">
                <h6>الوصف:</h6>
                <p><?php echo e($center->description); ?> </p>
            </div>
        </div>
		 <div class="col-3">
            <div class="qr">
                <?php if($center->code): ?>
							<?php echo QrCode::size(80)->backgroundColor(255,255,204)->generate($center->code); ?>

						<?php else: ?> 
					
							لايوجد كود للمركز
					<?php endif; ?>
	
            </div>
        </div>
    </div>
</div>
            </div>
            <!--end setting-->
            <!--start foter-->
           <div class="foter">
                <div class="row">
                    <div class="col-12 text-center">
                        <h5>Made With <img src="<?php echo e(asset('images/red.svg')); ?>"> By Crazy Idea </h5>
                        <p>Think Out Of The Box</p>
                    </div>
                </div>
            </div>
            <!--end foter-->
        </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('App.dash', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\Coursat\resources\views/dashboard/centerprofile.blade.php ENDPATH**/ ?>