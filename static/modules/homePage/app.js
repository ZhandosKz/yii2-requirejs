define(function() {
    return {
        title: "Home page",
        render: function() {
            $("head title").text(this.title);
            $(".home-app").text("Home page application");
        }
    };
});