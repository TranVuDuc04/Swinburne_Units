package com.example.musicrenting

import android.os.Bundle
import android.widget.TextView
import androidx.appcompat.app.AppCompatActivity

class PrintingReceiptActivity : AppCompatActivity() {

    private lateinit var instrumentName: String
    private var quantity: Int = 0
    private lateinit var condition: String
    private var totalPrice: Float = 0.0f
    private lateinit var invoiceNumber: String
    private lateinit var currentDate: String

    private lateinit var instrumentTextView: TextView
    private lateinit var quantityTextView: TextView
    private lateinit var conditionTextView: TextView
    private lateinit var totalPriceTextView: TextView
    private lateinit var invoiceNumberTextView: TextView
    private lateinit var dateTextView: TextView

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_printing_receipt)

        instrumentTextView = findViewById(R.id.instrumentTextView)
        quantityTextView = findViewById(R.id.quantityTextView)
        conditionTextView = findViewById(R.id.conditionTextView)
        totalPriceTextView = findViewById(R.id.totalPriceTextView)
        invoiceNumberTextView = findViewById(R.id.invoiceNumberTextView)
        dateTextView = findViewById(R.id.dateTextView)

        //restore data from saved instance state
        if (savedInstanceState != null) {
            instrumentName = savedInstanceState.getString("INSTRUMENT_NAME") ?: "N/A"
            quantity = savedInstanceState.getInt("QUANTITY", 0)
            condition = savedInstanceState.getString("CONDITION") ?: "N/A"
            totalPrice = savedInstanceState.getFloat("TOTAL_PRICE", 0.0f)
            invoiceNumber = savedInstanceState.getString("INVOICE_NUMBER") ?: "N/A"
            currentDate = savedInstanceState.getString("CURRENT_DATE") ?: "N/A"
        } else {
            instrumentName = intent.getStringExtra("INSTRUMENT_NAME") ?: "N/A"
            quantity = intent.getIntExtra("QUANTITY", 0)
            condition = intent.getStringExtra("CONDITION") ?: "N/A"
            totalPrice = intent.getFloatExtra("TOTAL_PRICE", 0.0f)
            invoiceNumber = "TVD-${System.currentTimeMillis()}"
            currentDate = android.text.format.DateFormat.format("dd/MM/yyyy", java.util.Date()).toString()
        }

        updateTextViews()
    }

    private fun updateTextViews() {
        invoiceNumberTextView.text = "Invoice No: $invoiceNumber"
        dateTextView.text = "Date: $currentDate"
        instrumentTextView.text = instrumentName
        quantityTextView.text = quantity.toString()
        conditionTextView.text = condition
        totalPriceTextView.text = "$%.2f".format(totalPrice)
    }

    //save instance state to preserve data
    override fun onSaveInstanceState(outState: Bundle) {
        super.onSaveInstanceState(outState)
        outState.putString("INSTRUMENT_NAME", instrumentName)
        outState.putInt("QUANTITY", quantity)
        outState.putString("CONDITION", condition)
        outState.putFloat("TOTAL_PRICE", totalPrice)
        outState.putString("INVOICE_NUMBER", invoiceNumber)
        outState.putString("CURRENT_DATE", currentDate)
    }
}
