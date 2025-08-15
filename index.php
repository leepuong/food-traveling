<?php include('partials-front/menu.php'); ?>
<?php include('partials-front/rightMenu.php'); ?>
<?php include('partials-front/searchBar.php'); ?>

<!-- Thêm link Font Awesome để icon trái tim hiện -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<?php
if (isset($_SESSION['order'])) {
    echo $_SESSION['order'];
    unset($_SESSION['order']);
}
?>

<!-- Categories Section Starts Here -->
<section class="categories">
    <div class="container">
        <h2 class="text-center">Explore Foods</h2>
        <?php
        $sql = "SELECT * FROM tbl_category WHERE active='Yes' AND featured='Yes' LIMIT 3";
        $res = mysqli_query($conn, $sql);
        $count = mysqli_num_rows($res);

        if ($count > 0) {
            while ($row = mysqli_fetch_assoc($res)) {
                $id = $row['id'];
                $title = $row['title'];
                $image_name = $row['image_name'];
        ?>
                <a href="<?php echo SITEURL; ?>category-foods.php?category_id=<?php echo $id; ?>">
                    <div class="box-3 float-container">
                        <?php if ($image_name != "") { ?>
                            <img src="<?php echo SITEURL; ?>images/category/<?php echo $image_name; ?>" class="img-responsive img-curve">
                        <?php } else { ?>
                            <div class='error'>Image not Available</div>
                        <?php } ?>
                        <h3 class="float-text text-white"><?php echo $title; ?></h3>
                    </div>
                </a>
        <?php
            }
        } else {
            echo "<div class='error'>Category not Added.</div>";
        }
        ?>
        <div class="clearfix"></div>
    </div>
</section>
<!-- Categories Section Ends Here -->

<!-- fOOD MEnu Section Starts Here -->
<section class="food-menu">
    <div class="container">
        <h2 class="text-center">Food Menu</h2>
        <?php
        // Lấy danh sách thích từ session
        $liked = $_SESSION['liked'] ?? [];

        $sql2 = "SELECT * FROM tbl_food WHERE active='Yes' AND featured='Yes' LIMIT 6";
        $res2 = mysqli_query($conn, $sql2);
        $count2 = mysqli_num_rows($res2);

        if ($count2 > 0) {
            while ($row = mysqli_fetch_assoc($res2)) {
                $id = $row['id'];
                $title = $row['title'];
                $price = $row['price'];
                $description = $row['description'];
                $image_name = $row['image_name'];
        ?>
                <div class="food-menu-box">
                    <div class="food-menu-img">
                        <?php if ($image_name != "") { ?>
                            <img src="<?php echo SITEURL; ?>images/food/<?php echo $image_name; ?>" class="img-responsive img-curve">
                        <?php } else { ?>
                            <div class='error'>Image not available.</div>
                        <?php } ?>
                    </div>

                    <div class="food-menu-desc">
                        <h4><?php echo $title; ?></h4>
                        <p class="food-price">$<?php echo $price; ?></p>
                        <p class="food-detail ellipsis"><?php echo $description; ?></p>
                        <br>

                        <!-- Nút order -->
                        <a href="<?php echo SITEURL; ?>order.php?food_id=<?php echo $id; ?>" class="btn btn-primary">Add to order</a>

                        <!-- Icon trái tim -->
                        <span class="heart-icon <?php echo in_array($id, $liked) ? 'liked' : ''; ?>" data-id="<?php echo $id; ?>">
                            <i class="fa-solid fa-heart"></i>
                        </span>
                    </div>
                </div>
        <?php
            }
        } else {
            echo "<div class='error'>Food not available.</div>";
        }
        ?>
        <div class="clearfix"></div>
    </div>
    <p class="text-center">
        <a href="#">See All Foods</a>
    </p>
</section>

<!-- CSS trái tim -->
<style>
.heart-icon {
    cursor: pointer;
    color: grey;
    font-size: 20px;
    margin-left: 10px;
    display: inline-block;
    vertical-align: middle;
}
.heart-icon.liked {
    color: red;
}
</style>

<!-- JS xử lý toggle like -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).on('click', '.heart-icon', function(){
    var heart = $(this);
    var productId = heart.data('id');

    $.post('toggleLike.php', {id: productId}, function(response){
        if(response.status === 'liked'){
            heart.addClass('liked');
        } else if(response.status === 'unliked'){
            heart.removeClass('liked');
        }
    }, 'json');
});
</script>
