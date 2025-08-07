$(document).ready(function() {
                           

                $('.add-to-cart-form').on('submit', function(e) {
                    e.preventDefault();
                    var form = $(this);
                    var productId = form.data('product-id');
                    var quantity = form.find('.product-quantity-input').val();
                    var button = form.find('.add-to-cart-btn'); // Target the button specifically

                    $.ajax({
                        url: form.attr('action'),
                        method: 'POST',
                        data: {
                            _token: csrfToken,
                            quantity: quantity
                        },
                        success: function(response) {
                            var messageContainer = $('#ajax-message-container');
                            messageContainer.empty();
                            if (response.success) {
                                messageContainer.html('<div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">' + response.message + '</div>');
                                $('#cart-count').text(response.cartCount);
                                
                                // Replace the button with "Go to Cart" link with red background
                                button.replaceWith(goToCartButton);
                                // Disable the quantity input
                                form.find('.product-quantity-input').prop('disabled', true);
                            }
                        },
                        error: function(xhr) {
                            var messageContainer = $('#ajax-message-container');
                            messageContainer.empty();
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                messageContainer.html('<div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">' + xhr.responseJSON.message + '</div>');
                            } else {
                                messageContainer.html('<div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">An error occurred. Please try again.</div>');
                            }
                            console.error('AJAX error:', xhr);
                        }
                    });
                });
            });