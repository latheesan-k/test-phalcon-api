define({ "api": [
  {
    "type": "post",
    "url": "/files",
    "title": "Create",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "filename",
            "description": "<p>Name of the csv file you're uploading</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "filedata",
            "description": "<p>Raw text content of your csv file</p>"
          }
        ]
      }
    },
    "version": "0.0.1",
    "name": "Create",
    "group": "Files",
    "success": {
      "fields": {
        "201": [
          {
            "group": "201",
            "type": "String",
            "optional": false,
            "field": "id",
            "description": "<p>Unique record identifier</p>"
          },
          {
            "group": "201",
            "type": "String",
            "optional": false,
            "field": "original_filename",
            "description": "<p>Original name of the uploaded file</p>"
          },
          {
            "group": "201",
            "type": "String",
            "optional": false,
            "field": "new_filename",
            "description": "<p>System generated unique file name</p>"
          },
          {
            "group": "201",
            "type": "Object",
            "optional": false,
            "field": "filesize",
            "description": "<p>File size information object</p>"
          },
          {
            "group": "201",
            "type": "String",
            "optional": false,
            "field": "filesize.bytes",
            "description": "<p>Size of the file in bytes</p>"
          },
          {
            "group": "201",
            "type": "String",
            "optional": false,
            "field": "filesize.formatted",
            "description": "<p>Human readable file size</p>"
          },
          {
            "group": "201",
            "type": "String",
            "optional": false,
            "field": "download_link",
            "description": "<p>Link to download this file</p>"
          },
          {
            "group": "201",
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
          "content": "HTTP/1.1 201 Created\n{\n    \"id\":\"1\",\n    \"original_filename\":\"test.csv\",\n    \"new_filename\":\"test.xls\",\n    \"filesize\":{\n        \"bytes\":\"8281\",\n        \"formatted\":\"8.09KB\"\n    },\n    \"download_link\":\"http://test-phalcon-api.local/download/test.xls\",\n    \"date_created\":\"2017-07-22\"\n}",
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
    "filename": "src/uploaded_files.create.js",
    "groupTitle": "Files"
  },
  {
    "type": "get",
    "url": "/files/:uploaded_file_id",
    "title": "Info",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "uploaded_file_id",
            "description": "<p>Uploaded File unique ID</p>"
          }
        ]
      }
    },
    "version": "0.0.1",
    "name": "Info",
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
          "content": "HTTP/1.1 200 OK\n{\n    \"id\":\"1\",\n    \"original_filename\":\"test.csv\",\n    \"new_filename\":\"test.xls\",\n    \"filesize\":{\n        \"bytes\":\"8281\",\n        \"formatted\":\"8.09KB\"\n    },\n    \"download_link\":\"http://test-phalcon-api.local/download/test.xls\",\n    \"date_created\":\"2017-07-22\"\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "fields": {
        "404": [
          {
            "group": "404",
            "type": "String",
            "optional": false,
            "field": "error",
            "description": "<p>Information about the error</p>"
          }
        ],
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
          "content": "HTTP/1.1 404 Internal Server Error\n{\n    \"error\": \"File not found.\"\n}",
          "type": "json"
        },
        {
          "title": "Error-Response:",
          "content": "HTTP/1.1 500 Internal Server Error\n{\n    \"error\": \"Reason why this error occurred\"\n}",
          "type": "json"
        }
      ]
    },
    "filename": "src/uploaded_files.info.js",
    "groupTitle": "Files"
  },
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
          "content": "HTTP/1.1 200 OK\n[\n    {\n        \"id\":\"1\",\n        \"original_filename\":\"test.csv\",\n        \"new_filename\":\"test.xls\",\n        \"filesize\":{\n            \"bytes\":\"8281\",\n            \"formatted\":\"8.09KB\"\n        },\n        \"download_link\":\"http://test-phalcon-api.local/download/test.xls\",\n        \"date_created\":\"2017-07-22\"\n    }\n]",
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
    "filename": "src/uploaded_files.list.js",
    "groupTitle": "Files"
  }
] });
