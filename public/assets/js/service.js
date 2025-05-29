class ApiService {
    constructor() {
        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
        });
    }
    url() {
        var getUrl = window.location;
        var baseurl = getUrl.origin;
        return getUrl.origin + '/' + getUrl.pathname.split('/')[1];
    }

    setFormData(subUrl, form) {
        return Promise.resolve(
            $.ajax({
                url: subUrl,
                method: "POST",
                data: new FormData(form),
                contentType: false,
                processData: false,
                cache: false,
            })
        );
    }
    setData(Url, data) {
        return Promise.resolve(
            $.ajax({
                url: Url,
                method: "POST",
                data: JSON.stringify(data),
                dataType: "json",
                contentType: "application/json;",
            })
        );
    }
    // setData(subUrl, data) {
    //     return Promise.resolve(
    //         $.ajax({
    //             url: subUrl,
    //             method: "POST",
    //             data: JSON.stringify(data),
    //             dataType: "json",
    //             contentType: "application/json;",
    //         })
    //     );
    // }
    getData(url, data) {
        return Promise.resolve(
            $.ajax({
                url: url,
                type: "GET",
                data: data,
                dataType: "json",
                contentType: "application/json;",
            })
        );
    }


}
