script type="text/javascript">
  function setCookie(name, value, days) {
    var date = new Date();
    date.setTime(date.getTime() + days * 24 * 60 * 60 * 1000);
    var expires = "; expires=" + date.toUTCString(); // Atualizado para padrão moderno
    document.cookie = name + "=" + value + expires + ";path=/";
  }

  function getParam(p) {
    var params = new URLSearchParams(window.location.search);
    return params.get(p) ? decodeURIComponent(params.get(p)) : null;
  }

  // Captura gclid OU gbraid (qualquer um dos dois)
  var gclid = getParam("gclid") || getParam("GCLID") || getParam("gclId");
  var gbraid = getParam("gbraid") || getParam("GBRAID") || getParam("gBraid");

  // Define prioridade: se gclid existir, usa ele. Se não, usa gbraid.
  var trackingId = gclid || gbraid;

  if (trackingId) {
    var gclsrc = getParam("gclsrc");
    if (!gclsrc || gclsrc.indexOf("aw") !== -1) {
      setCookie("gclid", trackingId, 90);
    }
  }

  function readCookie(name) {
    var n = name + "=";
    var cookie = document.cookie.split(";");
    for (var i = 0; i < cookie.length; i++) {
      var c = cookie[i].trim();
      if (c.indexOf(n) == 0) {
        return c.substring(n.length, c.length);
      }
    }
    return null;
  }

  document.addEventListener("DOMContentLoaded", function () {
    const gclidFields = document.getElementsByName("GCLID_FLD");
    for (var i = 0; i < gclidFields.length; i++) {
      gclidFields[i].value = readCookie("gclid");
    }

    var anchors = document.querySelectorAll("[whenClicked='set']");
    for (var i = 0; i < anchors.length; i++) {
      anchors[i].onclick = function () {
        var global_email = document.getElementById("email").value;
        var global_gclid = document.getElementById("GCLID_FLD").value;
        var global_gclid_wp = document.getElementById("GCLID_FLD_WP").value;
        window.localStorage.setItem("GCLID_FLD", global_gclid);
        window.localStorage.setItem("GCLID_FLD_WP", global_gclid_wp);
        window.localStorage.setItem("EMAIL_FLD", global_email);
      };
    }
  });
  
  document.addEventListener("DOMContentLoaded", function () {
  var gclidCookie = (document.cookie.match(/(^|;)\s*gclid=([^;]+)/) || [])[2];
  if (gclidCookie) {
    window.CollectChatAttributes = {
      gclid: decodeURIComponent(gclidCookie)
    };
    console.log("GCLID enviado ao Collect.chat:", decodeURIComponent(gclidCookie));
  }
});
  
</script>