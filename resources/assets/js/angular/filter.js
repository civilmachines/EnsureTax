/**
 * Created by Admin on 4/6/2017.
 */
app
// .filter('parseParent', ['$filter', function ($filter) {
//     return function (input, value) {
//         if (input)
//             var data = $filter('filter')(input, {value: value});
//         if (data.length > 0) {
//             return $filter('filter')(input, {parent: data[0].value});
//         }
//         return false;
//         // return angular.fromJson(input);
//     }
// }])
    .filter('proImageUrl', ['$CONFIG', '$filter', function ($CONFIG, $filter) {
        return function (input) {
            if (input && input.hasOwnProperty('image')) {
                var fileExt = input.image.split('.').pop();
                if (fileExt == 'pdf') {
                    return $CONFIG.PDF_DEFAULT_IMAGE;
                }
                return $CONFIG.PRO_IMAGE_URL + input.agent_id + '/' + input.category + '/' + input.image;
            }else if (input && input.hasOwnProperty('url'))
                return input.url;
        };
    }])
    .filter('downloadProImage', ['$CONFIG', '$filter', function ($CONFIG, $filter) {
        return function (input) {
            if (input && input.hasOwnProperty('image')) {
                return $CONFIG.PRO_IMAGE_URL + input.agent_id + '/' + input.category + '/' + input.image;
            }else if (input && input.hasOwnProperty('url'))
                return input.url;
        };
    }])
    .filter('imageUrl', ['$CONFIG', '$filter', function ($CONFIG, $filter) {
        return function (input) {
            if (input && input.hasOwnProperty('image')) {
                var fileExt = input.image.split('.').pop();
                if (fileExt == 'pdf') {
                    return $CONFIG.PDF_DEFAULT_IMAGE;
                }
                return $CONFIG.IMAGE_URL + input.itr_id + '/' + input.category + '/' + input.image;
            }
            else if (input && input.hasOwnProperty('url')) {
                return input.url;
            }
        };
    }])

    .filter('downloadImg', ['$CONFIG', '$filter', function ($CONFIG, $filter) {
        return function (input) {
            if (input && input.hasOwnProperty('image')) {

                return $CONFIG.IMAGE_URL + input.itr_id + '/' + input.category + '/' + input.image;
            }
            else if (input && input.hasOwnProperty('url')) {
                return input.url;
            }
        };
    }])

    .filter('corpImageUrl', ['$CONFIG', '$filter', function ($CONFIG, $filter) {
        return function (input) {
            if (input && input.hasOwnProperty('image')) {
                var fileExt = input.image.split('.').pop();
                if (fileExt == 'pdf') {
                    return $CONFIG.PDF_DEFAULT_IMAGE;
                }
                return $CONFIG.CORPORATE_IMAGE_URL + input.itr_id + '/' + input.category + '/' + input.image;
            }
            else if (input && input.hasOwnProperty('url')) {
                return input.url;
            }
        };
    }])

    .filter('downCorpImage', ['$CONFIG', '$filter', function ($CONFIG, $filter) {
        return function (input) {
            if (input && input.hasOwnProperty('image')) {
                return $CONFIG.CORPORATE_IMAGE_URL + input.itr_id + '/' + input.category + '/' + input.image;
            }else if (input && input.hasOwnProperty('url'))
                return input.url;
        };
    }])

    .filter('gstImageUrl', ['$CONFIG', '$filter', function ($CONFIG, $filter) {
        return function (input) {
            if (input && input.hasOwnProperty('image')) {
                var fileExt = input.image.split('.').pop();
                if (fileExt == 'pdf') {
                    return $CONFIG.PDF_DEFAULT_IMAGE;
                }
                return $CONFIG.GST_IMAGE_URL + input.agent_id + '/' + input.category + '/' + input.image;
            }
            else if (input && input.hasOwnProperty('url')) {
                return input.url;
            }
        };
    }])
    .filter('downGSTImg', ['$CONFIG', '$filter', function ($CONFIG, $filter) {
        return function (input) {
            if (input && input.hasOwnProperty('image')) {

                return $CONFIG.GST_IMAGE_URL + input.agent_id + '/' + input.category + '/' + input.image;
            }
            else if (input && input.hasOwnProperty('url')) {
                return input.url;
            }
        };
    }])

    .filter('money', function () {
        return function (input) {
            return Math.abs(Number(input)) >= 1.0e+7
                ? Math.abs(Number(input)) / 1.0e+7 + " Crore"
                // five Zeroes for Lakh
                : Math.abs(Number(input)) >= 1.0e+5

                ? Math.abs(Number(input)) / 1.0e+5 + " Lakh"
                // Three Zeroes for Thousands
                : Math.abs(Number(input)) >= 1.0e+3

                ? Math.abs(Number(input)) / 1.0e+3 + " K"

                : Math.abs(Number(input));
        };
    })
    .filter('datetime', ['$filter', function ($filter) {
        return function (input) {
            if (input == null) {
                return "";
            }
            var _date = $filter('date')(new Date(input), 'longDate');
            return _date;

        };
    }])
    .filter('parseParent', ['$filter', function ($filter) {
        return function (input, parent) {
            var items = {};
            if (parseInt(parent) > 0) {
                items = $filter('filter')(input, {parent: parent});
            } else {
                items['prop_types'] = [];
                input.map(function (row) {
                    if (row.parent == parent) {
                        items[row.text.toLowerCase()] = input.filter(function (data) {
                            if (data.parent == row.value)
                                return data;
                        })
                        if (row.text.toLowerCase() == 'residential' || row.text.toLowerCase() == 'commercial')
                            items['prop_types'].push(row);
                    }
                })
            }
            return items;
        }
    }])
    .filter('parseImageCategory', ['$filter', function ($filter) {
        return function (input, cat) {
            var items = {};
            if (input.length > 0)
                items = input.filter(function (data) {
                    if (data.category == parseInt(cat))
                        return data;
                })
            return items;
        }
    }])
    .filter('wordLimit', function () {
        return function (value, wordwise, max, tail) {
            if (!value) return '';

            max = parseInt(max, 10);
            if (!max) return value;
            if (value.length <= max) return value;

            value = value.substr(0, max);
            if (wordwise) {
                var lastspace = value.lastIndexOf(' ');
                if (lastspace !== -1) {
                    //Also remove . and , so its gives a cleaner result.
                    if (value.charAt(lastspace - 1) === '.' || value.charAt(lastspace - 1) === ',') {
                        lastspace = lastspace - 1;
                    }
                    value = value.substr(0, lastspace);
                }
            }

            return value + (tail || ' …');
        };
    });