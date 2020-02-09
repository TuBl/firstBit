$("#myButton").on("click", async function() {
  let res = await $.ajax({
    url: `/first/backup`
  });

  if (res) {
    $("#progress").show();
    let percentage = 0;
    while (percentage < 100) {
      percentage = await $.ajax({
        url: `/first/progress?progress=${percentage}`,
        success: function() {}
      });
      console.log(percentage);
    }
    if (percentage == 100) {
      window.location.assign("/first/success");
    }
  } else {
    window.location.assign("/first/fail");
  }
});

$("completed").on("click", function() {
  $("#completed").hide();
});
