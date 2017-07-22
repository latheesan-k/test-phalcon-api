define({ "api": [
  {
    "type": "get",
    "url": "/files",
    "title": "List",
    "version": "0.0.1",
    "name": "List",
    "group": "Files",
    "success": {
      "fields": {
        "200": [
          {
            "group": "200",
            "type": "String",
            "optional": false,
            "field": "id",
            "description": "<p>Unique record identifier</p>"
          },
          {
            "group": "200",
            "type": "String",
            "optional": false,
            "field": "original_filename",
            "description": "<p>Original name of the uploaded file</p>"
          },
          {
            "group": "200",
            "type": "String",
            "optional": false,
            "field": "new_filename",
            "description": "<p>System generated unique file name</p>"
          },
          {
            "group": "200",
            "type": "Object",
            "optional": false,
            "field": "filesize",
            "description": "<p>File size information object</p>"
          },
          {
            "group": "200",
            "type": "String",
            "optional": false,
            "field": "filesize.bytes",
            "description": "<p>Size of the file in bytes</p>"
          },
          {
            "group": "200",
            "type": "String",
            "optional": false,
            "field": "filesize.formatted",
            "description": "<p>Human readable file size</p>"
          },
          {
            "group": "200",
            "type": "String",
            "optional": false,
            "field": "download_link",
            "description": "<p>Link to download this file</p>"
          },
          {
            "group": "200",
            "type": "String",
            "optional": false,
            "field": "date_created",
            "description": "<p>Date of when the file was created (yyyy-mm-dd format)</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 200 OK\n[\n    {\n        \"id\": 1,\n        \"original_filename\":\"test.csv\",\n        \"new_filename\":\"test.xls\",\n        \"filesize\":{\n             \"bytes\":1234,\n             \"formatted\":\"0.12kb\"\n        },\n        \"download_link\":\"http://site.local/downloads/test.xls\",\n        \"date_created\":\"2017-07-21\"\n    }\n]",
          "type": "json"
        }
      ]
    },
    "error": {
      "fields": {
        "500": [
          {
            "group": "500",
            "type": "String",
            "optional": false,
            "field": "error",
            "description": "<p>Information about the error</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Error-Response:",
          "content": "HTTP/1.1 500 Internal Server Error\n{\n    \"error\": \"Reason why this error occurred\"\n}",
          "type": "json"
        }
      ]
    },
    "filename": "src/files.list.js",
    "groupTitle": "Files"
  }
] });
