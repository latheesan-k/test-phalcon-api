/**
 * @api {get} /files Create
 * @apiVersion 0.0.1
 * @apiName Create
 * @apiGroup Files
 *
 * @apiSuccess (200) {String} id Unique record identifier
 * @apiSuccess (200) {String} original_filename Original name of the uploaded file
 * @apiSuccess (200) {String} new_filename System generated unique file name
 * @apiSuccess (200) {Object} filesize File size information object
 * @apiSuccess (200) {String} filesize.bytes Size of the file in bytes
 * @apiSuccess (200) {String} filesize.formatted Human readable file size
 * @apiSuccess (200) {String} download_link Link to download this file
 * @apiSuccess (200) {String} date_created Date of when the file was created (yyyy-mm-dd format)
 *
 * @apiSuccessExample Success-Response:
 *     HTTP/1.1 200 OK
 *     {
 *         "id":"1",
 *         "original_filename":"test.csv",
 *         "new_filename":"test.xls",
 *         "filesize":{
 *             "bytes":"8281",
 *             "formatted":"8.09KB"
 *         },
 *         "download_link":"http://app.local/download/1",
 *         "date_created":"2017-07-22"
 *     }
 *
 * @apiError (404) {String} error Information about the error
 *
 * @apiErrorExample Error-Response:
 *     HTTP/1.1 404 Internal Server Error
 *     {
 *         "error": "File not found."
 *     }
 *
 * @apiError (500) {String} error Information about the error
 *
 * @apiErrorExample Error-Response:
 *     HTTP/1.1 500 Internal Server Error
 *     {
 *         "error": "Reason why this error occurred"
 *     }
 */