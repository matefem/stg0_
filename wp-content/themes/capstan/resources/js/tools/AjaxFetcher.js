// import transitionLoader from '../components/TransitionLoader';

class AjaxFetcher {
    constructor() {}

    async call(url, formData = null, outputFormat = "json", extraHeaders = {}) {
        // transitionLoader.play();

        var params = {method: 'POST', credentials: 'include'};

        if (formData != null) {
            formData.append("action", url);
            params["body"] = new URLSearchParams(formData).toString();
            params["headers"] = Object.assign(extraHeaders, {'Content-type': 'application/x-www-form-urlencoded'});
        }
        else {
            let headers = new Headers();
            headers.set('Content-type', 'text/html');
            // if (Object.keys(extraHeaders).length) {
            //     for (var i in extraHeaders) {
            //         headers.set(i, ''+extraHeaders[i]);
            //     }
            // }
            params["header"] = headers;
            // params["headers"] = headers;
        }

        let response = await fetch(formData != null ? ADMIN_AJAX_URL : url, params);

        if (outputFormat == "json") {
            const result = await response.json();
            if (!result["success"]) {alert(result["errors"]);}

            // transitionLoader.stop();
            return result;
        }
        else {

            const result = await response.text();
            // transitionLoader.stop();
            return result;
        }
    }
};

let ajaxFetcher = new AjaxFetcher();
export default ajaxFetcher;