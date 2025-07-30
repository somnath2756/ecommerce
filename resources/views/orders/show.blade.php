<x-app-layout>
    <div class="min-h-screen">

        <!-- Main Content -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <h1 class="text-3xl font-bold text-gray-900 mb-8">Order #{{ $order->id }}</h1>

            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white shadow-md rounded-lg p-6">
                <div class="mb-6">
                    <h2 class="text-xl font-semibold text-gray-800">Order Details</h2>
                    <p class="text-gray-600">Date: {{ $order->created_at->format('Y-m-d H:i') }}</p>
                    <p class="text-gray-600">Status: {{ ucfirst($order->status) }}</p>
                    <p class="text-gray-600">Total: ₹{{ number_format($order->total_amount, 2) }}</p>
                    <h4 class="text-gray-600"><b>Shipping Address: </b>{{ ucfirst($order->shipping_address) }}</h4>
                </div>

                <h2 class="text-xl font-semibold text-gray-800 mb-4">Items</h2>
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($order->items as $item)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $item->product->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">₹{{ number_format($item->price, 2) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $item->quantity }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">₹{{ number_format($item->quantity * $item->price, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="mt-6">
                    <a href="{{ route('orders.index') }}" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">Back to Orders</a>
                </div>
                <div class="flex justify-end">
                    <button onclick="generateReceipt()" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">Download Receipt</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Include pdfmake and vfs_fonts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.10/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.10/vfs_fonts.min.js"></script>

    <script>

        // JavaScript function to convert number to words
        
        
        var a = ['','one ','two ','three ','four ', 'five ','six ','seven ','eight ','nine ','ten ','eleven ','twelve ','thirteen ','fourteen ','fifteen ','sixteen ','seventeen ','eighteen ','nineteen '];
        var b = ['', '', 'twenty','thirty','forty','fifty', 'sixty','seventy','eighty','ninety'];

        function inWords (num) {
            if ((num = num.toString()).length > 9) return 'overflow';
            n = ('000000000' + num).substr(-9).match(/^(\d{2})(\d{2})(\d{2})(\d{1})(\d{2})$/);
            if (!n) return; var str = '';
            str += (n[1] != 0) ? (a[Number(n[1])] || b[n[1][0]] + ' ' + a[n[1][1]]) + 'crore ' : '';
            str += (n[2] != 0) ? (a[Number(n[2])] || b[n[2][0]] + ' ' + a[n[2][1]]) + 'lakh ' : '';
            str += (n[3] != 0) ? (a[Number(n[3])] || b[n[3][0]] + ' ' + a[n[3][1]]) + 'thousand ' : '';
            str += (n[4] != 0) ? (a[Number(n[4])] || b[n[4][0]] + ' ' + a[n[4][1]]) + 'hundred ' : '';
            str += (n[5] != 0) ? ((str != '') ? 'and ' : '') + (a[Number(n[5])] || b[n[5][0]] + ' ' + a[n[5][1]]) + 'only ' : '';
            return str;
        }

        

        
        //RECEIPT GENERATE PART 
        function generateReceipt() {
            // Order data passed from Laravel to JavaScript
            
            const order = @json($order);
            const amtWord = inWords(parseFloat(order.total_amount));
            // Prepare order items for the table
            const items = order.items.map((item, index) => [
                (index + 1).toString(), // Sl. No.
                item.product.name, // Item/Description
                item.quantity.toString(), // Quantity
                '₹ '+parseFloat(item.price).toFixed(2), // Price/Unit
                '₹ 0.00', // Total Disc (placeholder)
                '0%', // Tax Rate (placeholder)
                '₹ '+(item.price * item.quantity).toFixed(2) // Amount
            ]);

            // Convert total_amount to words (simple implementation)
            const numberToWords = (num) => {
                // Basic number-to-words conversion (extend as needed)
                const units = ['', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine'];
                return num === 0 ? 'Zero' : units[Math.floor(num)] + ' Rupees';
            };

            const docDefinition = {
                content: [
                    {
                        text: 'Ojas Payment Receipt',
                        style: 'header',
                        alignment: 'center',
                        margin: [0, 0, 0, 10],
                        decoration: 'underline'
                    },
                    {
                        columns: [
                            {
                                width: '50%',
                                stack: [
                                    { text: `Receipt No.: ${new Date(order.created_at).toISOString().slice(0, 10).replace(/-/g, '')}${order.id.toString().padStart(4, '0')}`, style: 'label' },
                                    { text: `Order date: ${new Date(order.created_at).toLocaleDateString('en-US', { day: 'numeric', month: 'short', year: 'numeric' })}`, style: 'label' },
                                    { text: 'Payment Type: Online', style: 'label' }, // Static, adjust as needed
                                ]
                            },
                            {
                                width: '50%',
                                stack: [
                                    {
                                        canvas: [
                                            { type: 'rect', x: 0, y: 0, w: 100, h: 50, lineWidth: 1 }
                                        ]
                                    }
                                ]
                            }
                        ],
                        columnGap: 10
                    },
                    {
                        table: {
                            widths: ['*'],
                            body: [
                                [{ text: `Name: ${order.user.name}`, style: 'field' }],
                                [{ text: `Address: ${order.shipping_address}`, style: 'field' }],
                                [{ text: 'Phone No.: Not Provided', style: 'field' }], // Adjust if phone is available
                                [{ text: `Email ID: ${order.user.email}`, style: 'field' }]
                            ]
                        },
                        layout: 'noBorders',
                        margin: [0, 10, 0, 0]
                    },
                    {
                        table: {
                            widths: ['auto', '*', 'auto', 'auto', 'auto', 'auto', 'auto'],
                            body: [
                                [
                                    { text: 'Sl. No.', style: 'tableHeader' },
                                    { text: 'Item/Description', style: 'tableHeader' },
                                    { text: 'Quantity', style: 'tableHeader' },
                                    { text: 'Price/Unit (in Rs)', style: 'tableHeader' },
                                    { text: 'Total Disc (in Rs)', style: 'tableHeader' },
                                    { text: 'Tax Rate', style: 'tableHeader' },
                                    { text: 'Amount (in Rs)', style: 'tableHeader' }
                                ],
                                ...items
                            ]
                        },
                        margin: [0, 10, 0, 0]
                    },
                    {
                        table: {
                            widths: ['*', 'auto'],
                            body: [
                                [
                                    { text: 'Total', style: 'tableTotal' },
                                    { text: '₹ '+parseFloat(order.total_amount).toFixed(2), style: 'tableTotal' }
                                ]
                            ]
                        },
                        margin: [0, 10, 0, 0]
                    },
                    {
                        columns: [
                            {
                                width: '50%',
                                stack: [
                                    { text: 'Amount in Words: ₹ '+amtWord, style: 'label' },
                                    { text: 'Terms & Condition: Payment is final.', style: 'label' } // Static, adjust as needed
                                ]
                            },
                            {
                                width: '50%',
                                stack: [
                                    { text: `Amount: ₹ ${parseFloat(order.total_amount).toFixed(2)}`, style: 'label' },
                                    { text: 'Additional Charge: ₹ 0.00', style: 'label' }, // Placeholder
                                    { text: `TOTAL Amount: ₹ ${parseFloat(order.total_amount).toFixed(2)}`, style: 'label' }
                                ]
                            }
                        ],
                        columnGap: 10
                    },
                    {
                        text: 'Seal & Signature',
                        style: 'footer',
                        alignment: 'right',
                        margin: [0, 10, 0, 0]
                    },
                    {
                        text: 'Thank you for Business !!! Visit us Again !!!',
                        style: 'thankYou',
                        alignment: 'center',
                        margin: [0, 10, 0, 0]
                    }
                ],
                styles: {
                    header: { fontSize: 20, bold: true, fillColor: '#D3D3D3' },
                    label: { fontSize: 12 },
                    field: { fontSize: 12, italics: true },
                    tableHeader: { fontSize: 12, bold: true, fillColor: '#D3D3D3' },
                    tableTotal: { fontSize: 12, bold: true, fillColor: '#D3D3D3' },
                    footer: { fontSize: 12, italics: true },
                    thankYou: { fontSize: 14, bold: true }
                },
                pageSize: 'A4',
                pageMargins: [40, 40, 40, 40]
            };

            // Generate and download the PDF
            pdfMake.createPdf(docDefinition).download(`receipt-order-${order.id}.pdf`);
        }
    </script>
</x-app-layout>