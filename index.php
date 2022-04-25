<?php
require 'db_conn.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>To-Do List</title>
  <!-- Bootstrap CSS v5.0.2 -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

  <link rel="stylesheet" href="css/style.css">
</head>

<body class="container">
  <div class="bg-transparent my-3 mx-auto rounded main-section">
    <div class="bg-light my-0 mx-auto p-2 rounded add-section">
      <form action="app/add.php" method="POST" autocomplete="off">
        <?php if (isset($_GET['mess']) && $_GET['mess'] == 'error') { ?>
          <input type="text" name="title" class="d-block my-2 mx-auto py-0 px-1 border border-danger" placeholder="Bạn không được bỏ trống" />
          <button type="submit" class="d-block btn btn-primary border-0 rounded my-0 mx-auto">Thêm</button>

        <?php } else { ?>
          <input type="text" name="title" class="d-block my-2 mx-auto border border-secondary rounded py-0 px-1" placeholder="Công việc bạn muốn thêm là gì?" />
          <button type="submit" class="d-block btn btn-primary border-0 rounded my-0 mx-auto">Thêm</button>
        <?php } ?>
      </form>
    </div>
    <?php
    $todos = $conn->query("SELECT * FROM todos ORDER BY id DESC");
    ?>
    <div class="p-1 my-2 mx-auto overflow-auto bg-light rounded show-todo-section">
      <?php if ($todos->rowCount() <= 0) { ?>
        <div class="my-2 mx-auto py-3 px-2 rounded shadow todo-item">
          <div class="empty">
            <h2>Bạn không có công việc cần làm</h2>
          </div>
        </div>
      <?php } ?>

      <?php while ($todo = $todos->fetch(PDO::FETCH_ASSOC)) { ?>
        <div class="my-2 mx-auto py-3 px-2 rounded shadow todo-item">
          <span id="<?php echo $todo['id']; ?>" class="d-block text-center text-decoration-none remove-to-do">x</span>
          <?php if ($todo['checked']) { ?>
            <input type="checkbox" class="check-box" data-todo-id="<?php echo $todo['id']; ?>" checked />
            <h2 class="d-inline-block py-1 px-0 checked"><?php echo $todo['title'] ?></h2>
          <?php } else { ?>
            <input type="checkbox" data-todo-id="<?php echo $todo['id']; ?>" class="check-box" />
            <h2 class="d-inline-block py-1 px-0"><?php echo $todo['title'] ?></h2>
          <?php } ?>
          <br>
          <small class="d-block py-1 px-0 ps-3">Ngày thêm: <?php echo $todo['date_time'] ?></small>
        </div>
      <?php } ?>
    </div>
  </div>

  <script src="js/jquery-3.2.1.min.js"></script>

  <script>
    $(document).ready(function() {
      $('.remove-to-do').click(function() {
        const id = $(this).attr('id');

        $.post("app/remove.php", {
            id: id
          },
          (data) => {
            if (data) {
              $(this).parent().hide(600);
            }
          }
        );
      });

      $(".check-box").click(function(e) {
        const id = $(this).attr('data-todo-id');

        $.post('app/check.php', {
            id: id
          },
          (data) => {
            if (data != 'error') {
              const h2 = $(this).next();
              if (data === '1') {
                h2.removeClass('checked');
              } else {
                h2.addClass('checked');
              }
            }
          }
        );
      });
    });
  </script>

  <!-- Bootstrap JavaScript Libraries -->
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
</body>

</html>