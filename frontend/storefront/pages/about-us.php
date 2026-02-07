<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BookNest</title>
    <link rel="stylesheet" href="../css/base.css">
    <link rel="stylesheet" href="../css/layout.css">
    <link rel="stylesheet" href="../css/component.css">
    <link rel="stylesheet" href="../css/responsive.css">
    <script defer type="module" src="../js/main.js"></script>
    <script defer type="module" src="../js/components/productCard.js"></script>
</head>
<body>

    <?php include __DIR__ . '../../includes/header.php'?>

    <?php include __DIR__ . '../../includes/sidebar.php'?>

    <main>
        <section id="about-us">
            <div class="our-story-container">
                <figure>
                    <img src="../../../assets/images/about-us-photo.png" alt="booknest about us photo">
                </figure>
                <div class="content">
                    <h4>Our Story</h4>
                    <p>
                        Booknest was created with one simple belief: books should feel accessible, inspiring, and personal.
                        We&apos;re an online bookstore built for readers who love discovering new stories, revisiting timeless classics,
                        and building collections that reflect who they are.
                    </p>
                    <p>
                        At Booknest, we carefully curate a diverse range of titles across genres &mdash; from fiction and self-development
                        to academic and niche reads &mdash; making it easy to explore and find your next favorite book. Our platform is
                        designed to offer a smooth, modern shopping experience that lets readers browse, add to wishlist, and purchase
                        effortlessly.
                    </p>
                    <p>
                        We&apos;re more than just a bookstore &mdash; we&apos;re a growing community of curious minds.
                    </p>
                </div>
            </div>
            <div class="our-aim-container">
                <div class="content">
                    <h4>What we aim for ?</h4>
                    <p>
                        At Booknest, our aim is to make reading more accessible and enjoyable for everyone. We focus on offering
                        a thoughtfully organized collection, delivering a fast and reliable shopping experience, and building trust
                        through quality service and transparency.
                    </p>
                    <p>
                        We strive to combine technology and storytelling to create a seamless online experience where discovering
                        books feels exciting rather than overwhelming. Our long-term vision is to become a trusted digital destination
                        for readers &mdash; a place where every visit leads to inspiration.
                    </p>
                </div>
                <figure>
                    <img src="../../../assets/images/aim-photo.png" alt="booknest aim photo">
                </figure>
            </div>
        </section>
        <?php include __DIR__ . '/../includes/services.php' ?>
    </main>

    

    <?php include __DIR__ . '../../includes/footer.php'?>

</body>
</html>