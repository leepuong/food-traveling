<?php
// likeIndex.php
// Place this file in the same folder where your other partials exist.

if (session_status() === PHP_SESSION_NONE) session_start();

include('partials-front/menu.php');
include('partials-front/rightMenu.php');
include('partials-front/searchBar.php');
?>

<!-- Ensure Font Awesome (for heart icon) -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<?php
if (isset($_SESSION['order'])) {
    echo $_SESSION['order'];
    unset($_SESSION['order']);
}
?>

<section class="food-menu">
    <div class="container">
        <h2 class="text-center">Liked Food Menu</h2>

        <div id="liked-list">
        <?php
        // Get liked IDs from session
        $liked_ids = $_SESSION['liked'] ?? [];

        if (empty($liked_ids)) {
            // No liked items
            echo "<div class='error'>You haven't liked any food yet.</div>";
        } else {
            // Sanitize IDs
            $liked_ids = array_map('intval', $liked_ids);
            $ids_str = implode(',', $liked_ids);

            // Query only the liked foods
            $sql2 = "SELECT * FROM tbl_food WHERE id IN ($ids_str)";
            $res2 = mysqli_query($conn, $sql2);

            if ($res2 && mysqli_num_rows($res2) > 0) {
                // Output each liked food
                while ($row = mysqli_fetch_assoc($res2)) {
                    $id = (int)$row['id'];
                    $title = htmlspecialchars($row['title']);
                    $price = htmlspecialchars($row['price']);
                    $description = htmlspecialchars($row['description']);
                    $image_name = $row['image_name'];
        ?>
                    <div class="food-menu-box" data-id="<?php echo $id; ?>">
                        <div class="food-menu-img">
                            <?php if ($image_name == "") { ?>
                                <div class='error'>Image not available.</div>
                            <?php } else { ?>
                                <img src="<?php echo SITEURL; ?>images/food/<?php echo $image_name; ?>" 
                                     alt="<?php echo $title; ?>" class="img-responsive img-curve">
                            <?php } ?>
                        </div>

                        <div class="food-menu-desc">
                            <h4><?php echo $title; ?></h4>
                            <p class="food-price">$<?php echo $price; ?></p>
                            <p class="food-detail ellipsis"><?php echo $description; ?></p>
                            <br>

                            <!-- Heart icon: initially "liked" -->
                            <span class="heart-icon liked" data-id="<?php echo $id; ?>" title="Unfavorite">
                                <i class="fa fa-heart"></i>
                            </span>

                            <!-- Order button -->
                            <a href="<?php echo SITEURL; ?>order.php?food_id=<?php echo $id; ?>" class="btn btn-primary">Add to order</a>
                        </div>
                    </div>
        <?php
                } // end while
            } else {
                // If DB returned nothing (maybe items were deleted), show empty message
                echo "<div class='error'>You haven't liked any food yet.</div>";
            }
        }
        ?>
        </div> <!-- #liked-list -->

        <div class="clearfix"></div>
    </div>
</section>

<!-- Simple styles for the heart -->
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
/* optional: small spacing for layout */
.food-menu-desc .btn + .heart-icon { margin-left: 8px; }
</style>

<!-- JavaScript: event delegation, AJAX to toggleLike.php -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    var likedList = document.getElementById('liked-list');

    // Use event delegation so it works for dynamically removed items too
    likedList.addEventListener('click', function (e) {
        var heart = e.target.closest('.heart-icon');
        if (!heart) return;

        var id = heart.dataset.id;
        if (!id) return;

        // POST to toggleLike.php (must return JSON {status: 'liked'|'unliked'})
        fetch('toggleLike.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'id=' + encodeURIComponent(id)
        })
        .then(function (res) { return res.json(); })
        .then(function (data) {
            if (data.status === 'unliked') {
                // Remove the food box from the page
                var box = heart.closest('.food-menu-box');
                if (box) box.remove();

                // If no more liked items, show the empty message
                if (!likedList.querySelector('.food-menu-box')) {
                    likedList.innerHTML = "<div class='error'>You haven't liked any food yet.</div>";
                }
            } else if (data.status === 'liked') {
                // Normally shouldn't happen here (this page only unlikes),
                // but keep UI consistent
                heart.classList.add('liked');
            }
        })
        .catch(function (err) {
            console.error('Error toggling like:', err);
        });
    });
});
</script>
