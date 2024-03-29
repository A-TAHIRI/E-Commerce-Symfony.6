





if( app_environement == 'dev' ){

    var stripeToken = "{{ stripe_public_key_test }}";
 }

 else {
var stripeToken = "{{ stripe_public_key_live }}";
}



var stripe = Stripe(stripeToken);
var elements = stripe.elements();
var subscription = "{{ product.id }}";
var clientSecret = "{{ intentSecret }}";
var cardholderName = "{{ app.user.lastname }}";
var cardholderEmail = "{{ app.user.email }}";

var styleCustom = {
base: {
fontSize: '16px',
color: '#25332d'
}
}

// Monter notre form a l'objet Stripe
var card = elements.create('card', {style: styleCustom});
card.mount("#card-elements");

// Message Error
card.addEventListener('change', function (event) {
var displayError = document.getElementById('card-errors');

if (event.error) {
displayError.textContent = event.error.message;
} else {
displayError.textContent = '';
}
});

var form = document.getElementById('payment-form');

form.addEventListener('submit', function (event) {
event.preventDefault();

stripe.handleCardPayment(clientSecret, card, {
payment_method_data: {
billing_details: {
name: cardholderName,
email: cardholderEmail
}
}
}).then((result) => {
if (result.error) { // Display error
} else if ('paymentIntent' in result) {
stripeTokenHandler(result.paymentIntent);
}
})
});

function stripeTokenHandler(intent) {
var form = document.getElementById('payment-form');
var InputIntentId = document.createElement('input');
var InputIntentPaymentMethod = document.createElement('input');
var InputIntentStatus = document.createElement('input');
var InputSubscription = document.createElement('input');

InputIntentId.setAttribute('type', 'hidden');
InputIntentId.setAttribute('name', 'stripeIntentId');
InputIntentId.setAttribute('value', intent.id);

InputIntentPaymentMethod.setAttribute('type', 'hidden');
InputIntentPaymentMethod.setAttribute('name', 'stripeIntentPaymentMethod');
InputIntentPaymentMethod.setAttribute('value', intent.payment_method);

InputIntentStatus.setAttribute('type', 'hidden');
InputIntentStatus.setAttribute('name', 'stripeIntentStatus');
InputIntentStatus.setAttribute('value', intent.status);

InputSubscription.setAttribute('type', 'hidden');
InputSubscription.setAttribute('name', 'subscription');
InputSubscription.setAttribute('value', subscription);

form.appendChild(InputIntentId);
form.appendChild(InputIntentPaymentMethod);
form.appendChild(InputIntentStatus);
form.appendChild(InputSubscription);
form.submit();
}