const httpServer = require('http').createServer()
const io = require("socket.io")(httpServer, {
    cors: {
        // The origin is the same as the Vue app domain. Change if necessary
        origin: "http://localhost:5173",
        methods: ["GET", "POST"],
        credentials: true
    }
})
httpServer.listen(8080, () => {
    console.log('listening on *:8080')
})

let customersArr = []


io.on('connection', (socket) => {  //sempre k ha algo pro server de sockets esta func é chamada
    console.log(`client ${socket.id} has connected`)

    socket.on('loggedIn', function (user) {
        socket.join(user.id)

        switch (user.type) {
            case 'EM':
                socket.join('EMs')
                break
            case 'ED':
                socket.join('EDs')
                break
            case 'EC':
                socket.join('ECs')
                break;
            default:
                // customersArr.push(socket.id)
                socket.join('customers')
        }
    })

    socket.on('loggedOut', function (user) {
        socket.leave(user.id)
        switch (user.type) {
            case 'EM':
                socket.leave('EMs')
                break
            case 'ED':
                socket.leave('EDs')
                break
            case 'EC':
                socket.leave('ECs')
                break
            default:
                socket.leave('customers')
        }
    })

    socket.on('newOrder', function (orderId) {
        customersArr[orderId] = socket.id
        io.in('ECs').emit('newOrder', orderId)
    })

    socket.on('orderIsReadyWithHotDish', function (order) {
        let socketID = customersArr[order.id]
        io.emit(socketID, order)
        io.in('EDs').emit('orderReadyToDeliver', order)
    })

    socket.on('orderIsReadyWithoutHotDish', function (orderID) {
        order = {
            status: "R",
            id: orderID
        }
        io.emit(socket.id, order)
        io.in('EDs').emit('orderReadyToDeliver', order)
    })

    socket.on('deliverOrder', function (order) {
        let socketID = customersArr[order.id]
        io.emit(socketID, order)
    })
})
