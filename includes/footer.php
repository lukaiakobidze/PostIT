<footer>
    <hr>
    <p>&copy; <?= date('Y') ?> PostIT </p>
    <p> Made by Luka Iakobidze </p>
  </footer>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  $(document).ready(function () {
    $(".toggle-comments").click(function () {
      // Only toggle the next sibling .comments-section
      $(this).next(".comments-section").slideToggle();
    });
  });
</script>
</body>
</html>