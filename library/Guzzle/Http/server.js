/**
 * /* Replaced /* Replaced /* Replaced Guzzle */ */ */ node.js test server to return queued responses to HTTP requests and
 * expose a RESTful API for enqueueing responses and retrieving the requests
 * that have been received.
 *
 * - Delete all requests that have been received:
 *      DELETE //* Replaced /* Replaced /* Replaced guzzle */ */ */-server/requests
 *      Host: 127.0.0.1:8124
 *
 *  - Enqueue responses
 *      PUT //* Replaced /* Replaced /* Replaced guzzle */ */ */-server/responses
 *      Host: 127.0.0.1:8124
 *
 *      [{ "statusCode": 200, "reasonPhrase": "OK", "headers": {}, "body": "" }]
 *
 *  - Get the recevied requests
 *      GET //* Replaced /* Replaced /* Replaced guzzle */ */ */-server/requests
 *      Host: 127.0.0.1:8124
 *
 *  - Shutdown the server
 *      DELETE //* Replaced /* Replaced /* Replaced guzzle */ */ */-server
 *      Host: 127.0.0.1:8124
 *
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

var http = require("http");

/**
 * /* Replaced /* Replaced /* Replaced Guzzle */ */ */ node.js server
 * @class
 */
var /* Replaced /* Replaced /* Replaced Guzzle */ */ */Server = function(port) {

    this.port = port;
    this.responses = [];
    this.requests = [];
    var that = this;

    /**
     * Handle a /* Replaced /* Replaced /* Replaced Guzzle */ */ */ Server control request
     * @param (String) request HTTP request as a string
     * @param (ServerRequest) req Received server request
     * @param (ServerResponse) res Outgoing server response
     */
    var controlRequest = function(request, req, res) {
        if (req.method == "DELETE") {
            if (req.url == "//* Replaced /* Replaced /* Replaced guzzle */ */ */-server/requests") {
                // Clear the received requests
                that.requests = [];
                res.writeHead(200, "OK", { "Content-Length": 0 });
                res.end();
            } else if (req.url == "//* Replaced /* Replaced /* Replaced guzzle */ */ */-server") {
                // Shutdown the server
                res.writeHead(200, "OK", { "Content-Length": 0, "Connection": "close" });
                res.end();
                console.log("Shutting down");
                that.server.close();
            }
        } else if (req.method == "GET") {
            if (req.url === "//* Replaced /* Replaced /* Replaced guzzle */ */ */-server/requests") {
                // Get received requests
                var data = that.requests.join("\n----[request]\n");
                res.writeHead(200, "OK", { "Content-Length": data.length });
                res.end(data);
            }
        } else if (req.method == "PUT") {
            if (req.url == "//* Replaced /* Replaced /* Replaced guzzle */ */ */-server/responses") {
                // Received respones to queue
                that.responses = eval("(" + request.split("\r\n\r\n")[1] + ")");
                console.log("Adding respones:");
                console.log(that.responses);
                res.writeHead(200, "OK", { "Content-Length": 0 });
                res.end();
            }
        }
    };

    /**
     * Received a complete request
     * @param (String) request HTTP request as a string
     * @param (ServerRequest) req Received server request
     * @param (ServerResponse) res Outgoing server response
     */
    var receivedRequest = function(request, req, res) {
        if (req.url.indexOf("//* Replaced /* Replaced /* Replaced guzzle */ */ */-server") === 0) {
            controlRequest(request, req, res);
        } else {
            var response = that.responses.shift();
            res.writeHead(response.statusCode, response.reasonPhrase, response.headers);
            res.end(response.body);
            that.requests.push(request);
        }
    };

    /**
     * Start the node.js /* Replaced /* Replaced /* Replaced Guzzle */ */ */ server
     */
    this.start = function() {

        that.server = http.createServer(function(req, res) {

            // If this is not a control request and no responses are in queue, return 500 response
            if (req.url.indexOf("//* Replaced /* Replaced /* Replaced guzzle */ */ */-server") == -1 && !that.responses.length) {
                res.writeHead(500);
                res.end("No responses in queue");
                return;
            }

            // Begin building the request message as a string
            var request = req.method + " " + req.url + " HTTP/" + req.httpVersion + "\r\n";
            // Add the request headers
            for (var i in req.headers) {
                request += i + ": " + req.headers[i] + "\r\n";
            }
            request += "\r\n";

            // Receive each chunk of the request body
            req.addListener("data", function(chunk) {
                request += chunk;
            });

            // Called when the request completes
            req.addListener("end", function() {
                receivedRequest(request, req, res);
            });
        });
        that.server.listen(port, "127.0.0.1");

        console.log("Server running at http://127.0.0.1:8124/");
    };
};

// Get the port from the arguments
port = process.argv.length >= 3 ? process.argv[2] : 8124;

// Start the server
server = new /* Replaced /* Replaced /* Replaced Guzzle */ */ */Server(port);
server.start();