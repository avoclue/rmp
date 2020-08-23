<?php
    // functions
    $conn = mysqli_connect("localhost", "root", "", "rmp");

    function query($query){
        global $conn;
        $res = mysqli_query($conn, $query);
        $dataList = [];
        while($data = mysqli_fetch_assoc($res)) $dataList[] = $data;
        return $dataList;
    }
    
    function addCustomer($data){
        global $conn;

        $name = htmlspecialchars($data["name"]);
        $address = htmlspecialchars($data["address"]);
        $phone = htmlspecialchars($data["phone"]);
        $motor = htmlspecialchars($data["motor"]);

        $query = "INSERT INTO customer_tb
                    VALUES ('', '$name', '$address', '$phone', '$motor')";
        mysqli_query($conn, $query);

        return mysqli_affected_rows($conn);
    }

    function addProduct($data){
        global $conn;

        $name = htmlspecialchars($data["name"]);
        $brand = htmlspecialchars($data["brand"]);
        $image = htmlspecialchars($data["image"]);
        $color = htmlspecialchars($data["color"]);
        $specification = htmlspecialchars($data["specification"]);
        $stock = htmlspecialchars($data["stock"]);

        $query = "INSERT INTO motorcycle_tb
                    VALUES ('', '$name', '$brand', '$image', '$color', '$specification', '$stock')";
        mysqli_query($conn, $query);

        return mysqli_affected_rows($conn);
    }

    function addBrand($data){
        global $conn;

        $name = htmlspecialchars($data["name"]);

        $query = "INSERT INTO brand_tb VALUES ('', '$name')";
        mysqli_query($conn, $query);

        return mysqli_affected_rows($conn);
    }

    // buy
    function buy($data){
        global $conn;
        $id = $data["buy"];
        $query = "UPDATE motorcycle_tb SET stock = stock-1 WHERE id = $id";
        mysqli_query($conn, $query);

        return mysqli_affected_rows($conn);
    }

    if(isset($_GET["buy"])){
        if(buy($_GET) > 0) echo "
            <script>
                alert('Success!');
            </script>
        ";
        else echo "
            <script>
                alert('Error!')
            </script>
        ";
    }

    // ind
    $motors = query("SELECT * FROM motorcycle_tb");


    // add product
    $brands = query("SELECT * FROM brand_tb");

    // detail
    if(isset($_GET["id"])){
            $id = $_GET["id"];
            $motorDetail = query("SELECT * FROM motorcycle_tb WHERE id = $id");
            $bId = $motorDetail[0]["brand_id"];
            $brandDetail = query("SELECT * FROM brand_tb WHERE id = $bId");
        
    }

    if(isset($_POST["submit"])){
        if(addProduct($_POST) > 0) echo "
            <script>
                alert('Success!');
                document.location.href = 'index.php';
            </script>
            ";
        else echo "
            <script>
                alert('Error!')
            </script>
            ";
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
            scroll-behavior: smooth;
        }

        main {
            padding: 50px;
        }

        .container {
            display: flex;
            flex-wrap: wrap;
        }

        h1 {
        flex: 50%;
        font-size: 36px;
        }

        .btn {
            text-decoration: none;
            display: inline-block;
            padding: 10px;
            color: white;
            border-radius: 5px;
        }

        .add {
            margin-left: 5px;
            background-color: cornflowerblue;
        }

        .content {
            flex: 100%;
            margin-top: 25px;
        }

        .content .btn {
            margin-top: 5px;
        }

        .products {
            justify-items: center;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px,1fr));
            grid-gap: 20px;
            align-items: baseline;
        }

        .buy {
            background-color: yellowgreen;
        }

        .detail {
            background-color: tomato;
        }

        img {
            display: block;
            width: 200px;
            height: auto;
        }

        img.det {
            width: 400px;
        }

        table {
            border-collapse: collapse;
        }

        tr:nth-child(2n) {
            background-color: rgb(236, 236, 236);
        }

        td {
            padding: 10px;
            width: 200px;
            border: 1px solid rgb(221, 221, 221);
        }

        button {
            padding: 5px;
        }

        .top{
            background-color: seagreen;
        }

        .hide {
            display: none;
        }

        .show {
            display: none;
            margin-top: 50px;
        }

        .show:target {
            display: block;
        }
    </style>
    <title>RMP Motorcycle</title>
</head>

<body>
    <main>
        <div class="container">
            <h1>RMP Motorcycle</h1>
            <div class="add-btn-wrapper">
                <a class="btn add" href="#" onclick="toggleBtn('customer');">Add Customer</a>
                <a class="btn add" href="#" onclick="toggleBtn('product');"> Add Product</a>
                <a class="btn add" href="#" onclick="toggleBtn('brand');">Add Brand</a>
            </div>

            <div class="addCustomer-wrapper hide" id="addcustomer">
                <h1>Add Customer</h1>
                <div class="add-form content">
                    <form action="" method="post">
                        <div class="field">
                            <label for="name">Name</label>
                            <input type="text" name="name" id="name" required>
                        </div>
                        
                        <div class="field">
                            <label for="address">Address</label>
                            <textarea name="address" id="address" required></textarea>
                        </div>

                        <div class="field">
                            <label for="phone">Phone</label>
                            <input type="text" name="phone" id="phone" required>
                        </div>


                        <div class="field">
                            <label for="motor">Motorcycle</label>
                            <select name="motor" id="motor">
                            <?php foreach($motors as $motor) : ?>
                                    <option value="<?= $motor["id"]; ?>"><?= $motor["name"]; ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>

                        <button type="submit" name="submit">Add</button>
        
                    </form>
                </div>
                <a href="#" class="btn top">Top</a>
            </div>

            <div class="addProduct-wrapper hide" id="addproduct">
                <h1>Add Product</h1>
                <div class="add-form content">
                    <form action="" method="post">
                        <div class="field">
                            <label for="name">Motorcycle Name</label>
                            <input type="text" name="name" id="name" required>
                        </div>

                        <div class="field">
                            <label for="brand">Brand</label>
                            <select name="brand" id="brand">
                                <?php foreach($brands as $brand) : ?>
                                    <option value="<?= $brand["id"]; ?>"><?= $brand["name"]; ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>

                        <div class="field">
                            <label for="image">Image</label>
                            <input type="file" name="image" id="image" required>
                        </div>

                        <div class="field">
                            <label for="color">Color</label>
                            <input type="text" name="color" id="color" required>
                        </div>

                        <div class="field">
                            <label for="specification">Specification</label>
                            <input type="text" name="specification" id="specification" required>
                        </div>

                        <div class="field">
                            <label for="stock">Stock</label>
                            <input type="number" name="stock" id="stock" required>
                        </div>

                        <button type="submit" name="submit">Add</button>
                    </form>
                </div>
                <a href="#" class="btn top">Top</a>
            </div>

            <div class="addBrand-wrapper hide" id="addbrand">
                <h1>Add Brand</h1>
                <div class="add-form content">
                    <form action="" method="post">
                        <div class="field">
                            <label for="name">Brand Name</label>
                            <input type="text" name="name" id="name" required>
                        </div>

                        <button type="submit" name="submit">Add</button>
                    </form>
                </div>
                <a href="#" class="btn top">Top</a>
            </div>

            <div class="products content">
                <?php foreach($motors as $motor) : ?>
                    <div class="motor">
                        <img src="<?= $motor["image"]; ?>">
                        <h2><?= $motor["name"]; ?></h2>
                        <a href="?buy=<?= $motor["id"]; ?>" class="btn buy" name="buy" onclick="return confirm('Do you want to buy this motorcycle?');">Buy</a>
                        <a href="?id=<?= $motor['id']; ?>#detail" class="btn detail" id="toggleBtn" name="toggleBtn">See Details</a>
                    </div>
                <?php endforeach ?>
            </div>

            <div class="detail-wrapper show" id="detail">
                <h1>Details</h1>
                <div class="detail-container content">
                    <img class="det" src="<?= $motorDetail[0]["image"]; ?>">
                    <h2>Specification</h2>
                    <table>
                        <tr>
                            <td>Name</td>
                            <td><?= $motorDetail[0]["name"]; ?></td>
                        </tr>

                        <tr>
                            <td>Brand</td>
                            <td><?= $brandDetail[0]["name"]; ?></td>
                        </tr>

                        <tr>
                            <td>Color</td>
                            <td><?= $motorDetail[0]["color"]; ?></td>
                        </tr>

                        <tr>
                            <td>Specification</td>
                            <td><?= $motorDetail[0]["specification"]; ?></td>
                        </tr>

                        <tr>
                            <td>Stock</td>
                            <td><?= $motorDetail[0]["stock"]; ?></td>
                        </tr>
                    </table>
                </div>

                <a href="#" class="btn top">Top</a>
            </div>
      
        </div>
    </main>

    <script>
        function toggleBtn(a){
            id="add"+a;
            document.getElementById(id).classList.toggle('hide');
        }
    </script>

</body>
</html>
