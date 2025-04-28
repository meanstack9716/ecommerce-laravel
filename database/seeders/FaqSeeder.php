<?php

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $faqs = [
            // Account Management
            [
              'question' => "How do I create an account?",
              'answer' => "Click on 'Sign Up' at the top right corner, fill in your details, verify your email, and your account will be created.",
              'category'=> "Account"
            ],
            [
              'question'=> "I forgot my password. How can I reset it?",
              'answer'=> "Click 'Forgot Password' on the login page, enter your registered email, and follow the instructions sent to your inbox.",
              "category" => "Account"
            ],
            [
                'question' =>  "How do I update my mobile number/email?",
                'answer' =>  "Go to 'Account Settings' > 'Personal Info', and verify the new details via OTP.",
                'category' =>  "Account"
            ],

             // Products & Pricing
            [
                'question' =>  "Why are there different prices for the same product?",
                'answer' =>  "Prices may vary by seller, size, color, or ongoing promotions. All options are clearly displayed.",
                'category' =>  "Products"
            ],
            [
                'question' =>  "How do I sell a product on E_commerce?",
                'answer' =>  "Register as a seller, go to 'Seller Dashboard', and click 'Add Product' to create listings.",
                'category' =>  "Products"
            ],

            // Orders
            [
                'question' =>  "How do I cancel an order?",
                'answer' =>  "Go to 'My Orders' > select order > 'Cancel'. Refunds are processed within 3-5 business days.",
                'category' =>  "Orders"
            ],
            [
                'question' =>  "How do I track my order?",
                'answer' =>  "Check 'My Orders' for real-time tracking updates. You’ll also receive shipping emails.",
                'category' =>  "Orders"
            ],
            [
                'question' =>  "Why was my order delayed?",
                'answer' =>  "Delays can occur due to high demand, logistics issues, or weather. Check tracking for updates.",
                'category' =>  "Orders"
            ],

            // Returns & Refunds
            [
                'question' =>  "What is your return policy?",
                'answer' =>  "Returns are accepted within 30 days if the product is unused. Check our Returns Policy page for details.",
                'category' =>  "Returns"
            ],
            [
                'question' =>  "How do I return a product?",
                'answer' =>  "Go to 'My Orders' > select item > 'Return'. Choose pickup or self-ship options.",
                'category' =>  "Returns"
            ],
            [
                'question' =>  "When will my refund be processed?",
                'answer' =>  "Refunds are issued within 5-7 business days after we receive and inspect the returned item.",
                'category' =>  "Returns"
            ],
            [
                'question' =>  "Where do I self-ship returns?",
                'answer' =>  "You’ll receive a return address via email. Share the tracking ID after shipping.",
                'category' =>  "Returns"
            ],

            // Shipping & Delivery
            [
                'question' =>  "What are the shipping fees?",
                'answer' =>  "Fees depend on order value, location, and seller. Free shipping may apply for orders above ₹499.",
                'category' =>  "Shipping"
            ],
            [
                'question' =>  "How long does delivery take?",
                'answer' =>  "Standard delivery takes 3-7 days. Express options (1-2 days) may be available at checkout.",
                'category' =>  "Shipping"
            ],
            [
                'question' =>  "Do you deliver internationally?",
                'answer' =>  "No, we currently deliver only within India.",
                'category' =>  "Shipping"
            ],

            // Payments
            [
              'question' =>  "What payment methods do you accept?",
              'answer' =>  "We accept credit/debit cards, PayPal, bank transfers, and other secure payment options (varies by region).",
              'category' =>  "Payments"
            ],
            [
              'question' =>  "Is my payment information secure?",
              'answer' =>  "Yes, we use SSL encryption and trusted payment gateways to ensure your data is protected.",
              'category' =>  "Payments"
            ],
            [
                'question' =>  "Why was my payment declined?",
                'answer' =>  "This could be due to insufficient funds, bank restrictions, or incorrect details. Contact your bank for details.",
                'category' =>  "Payments"
            ],
            
            [
              'question' =>  "How do I contact customer support?",
              'answer' =>  "Visit the 'Contact Us' page or email support@example.com. We respond within 24 hours.",
              'category' =>  "Most Asked"
            ],
            [
              'question' =>  "What if I receive a damaged/wrong item?",
              'answer' =>  "Contact us within 48 hours with order details and photos. We’ll assist with a refund or replacement.",
              'category' =>  "Most Asked"
            ],
            [
              'question' =>  "How do I leave a product review?",
              'answer' =>  "After receiving your order, go to 'My Orders', select the product, and click 'Leave a Review'.",
              'category' =>  "Most Asked"
            ],
            [
                'question' =>  "What is E_commerce’s Fair Usage Policy?",
                'answer' =>  "It prevents misuse of discounts/free shipping to ensure fair access for all customers.",
                'category' =>  "Most Asked"
            ],
        ];

        foreach ($faqs as $faq) {
            Faq::updateOrCreate(
                ['question' => $faq['question']],
                $faq
            );
        }
    }
}
