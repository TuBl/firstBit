$("#myButton").on("click", function(id) {
  $.ajax({
    url: `/first/test?id=${id}`,
    success: function() {
      window.location.assign(`/first/test?id=${id}`);
    }
  });
});
