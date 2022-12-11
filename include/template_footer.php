</div>
<script>
    // Autocomplete - search
    $(function() {
        $("#search").autocomplete({
            source: "autocomplete.php?suggest=title",
            select: function(event, ui) {
            window.location.href = "title.php?t="+ui.item.id;
            },
            minLength: 2,
            delay: 300
        });
    });
    // Autocomplete - title
    $(function() {
        $("#title").autocomplete({
            source: "autocomplete.php?suggest=name",
            minLength: 2,
            delay: 300
        });
    });
    // Autocomplete - edition
    $(function() {
        $("#edition").autocomplete({
            source: "autocomplete.php?suggest=edition",
            minLength: 2,
            delay: 300
        });
    });
    // Scroll to hash
    $(function() {
        var hash = window.location.hash;
        if (hash) {
            $("html, body").animate({
                scrollTop: $(hash).offset().top
            },500);
        }
    });
    // Scroll to toggle element
    $(function() {
        $(".toggle-label").click(function() {
            $("html, body").animate({
                scrollTop: $(this).offset().top
            },500);
        });
    });
    // Selected option text to input field
    $(function() {
        $(".settings-sel").change(function() {
            var sel = $(this).prop("selectedIndex") ? $("option:selected", this).text() : "";
            $(this).next("input:text").val(sel);
        });
    });
    // Required input
    $(function() {
        $(".required").prop("required", true);
    });
    // Change submit to delete button and swap required fields if checkbox is checked
    $(function() {
        $(".del-check").click(function() {
            $(this).prevAll(".required").prop("required", !this.checked);
            $(this).prevAll(".settings-sel").prop("required", this.checked);
            var submit = $(this).prop("checked") ? "<?=DELETE?>" : "<?=SAVE?>";
            $(this).next("button:submit").html(submit);
        });
    });
    // Enable JQuery tooltip
    $(function() {
        $(document).tooltip({
            track: true
        });
    });
    // Prevent link action from truncated text icon
    $(function() {
        $(".eye").click(function(event) {
            event.preventDefault();
        });
    });
    // HTML Encode
    function htmlEncode(str) {
        return str.replace(/&/g, "&amp;")
                  .replace(/</g, "&lt;")
                  .replace(/>/g, "&gt;")
                  .replace(/'/g, "&#39;")
                  .replace(/"/g, "&#34;")
                  .replace(/\//g, "&#47;");
    }
    // Show selected filters
    function showFilter(label, element, type) {
        if (type === "value") {
            var input = $(element).val();
            var filter = input ? htmlEncode(input) : "";
        } else if (type === "text") {
            var filter = $(element).toArray().map(item => item.text).join(" | ");
        } else if (type === "date") {
            var filter = $(element).val() ? new Date($(element).val()).toLocaleDateString() : "";
        }
        if (filter) {
            $(".total span").append(" &nbsp;&bull;&nbsp; " + label + ": " + filter);
        } 
    }
    $(function() {
        showFilter("<?=TITLE?>", "#filter-title", "value");
        showFilter("<?=PUBLISHED?>", "#published option:selected", "text");
        showFilter("<?=PLATFORM?>", "#platform option:selected", "text");
        showFilter("<?=MEDIATYPE?>", "#mediatype option:selected", "text");
        showFilter("<?=TITLETYPE?>", "#titletype option:selected", "text");
        showFilter("<?=PAYMETHOD?>", "#paymethod option:selected", "text");
        showFilter("<?=STORE?>", "#store option:selected", "text");
        showFilter("<?=START_DATE?>", "#from", "date");
        showFilter("<?=END_DATE?>", "#to", "date");
    });
</script>
</body>
</html>