package com.example.moneyshare.activity

import android.content.Intent
import android.os.Bundle
import androidx.appcompat.app.AppCompatActivity
import androidx.recyclerview.widget.LinearLayoutManager
import com.example.moneyshare.data.Payment
import com.example.moneyshare.adapter.PaymentAdapter
import com.example.moneyshare.databinding.ActivityPaymentBinding
import com.google.firebase.database.DatabaseReference
import com.google.firebase.database.FirebaseDatabase
import com.google.firebase.database.ValueEventListener
import com.google.firebase.database.DataSnapshot
import com.google.firebase.database.DatabaseError
import android.util.Log

class PaymentActivity : AppCompatActivity() {

    private lateinit var binding: ActivityPaymentBinding
    private lateinit var database: DatabaseReference
    private lateinit var paymentAdapter: PaymentAdapter
    private val paymentList = mutableListOf<Payment>()
    private val TAG = "PaymentActivity"

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        binding = ActivityPaymentBinding.inflate(layoutInflater)
        setContentView(binding.root) // set content view

        // initialize firebase database reference
        database = FirebaseDatabase.getInstance().getReference("Payments")

        // retrieve participant info and event name from intent
        val participantName = intent.getStringExtra("PARTICIPANT_NAME")
        val eventName = intent.getStringExtra("EVENT_NAME")

        binding.tvParticipantName.text = participantName // display participant name
        binding.recyclerViewPayments.layoutManager = LinearLayoutManager(this)
        paymentAdapter = PaymentAdapter(paymentList)
        binding.recyclerViewPayments.adapter = paymentAdapter

        // load payments from firebase
        participantName?.let { loadPayments(eventName, it) }

        // set up add payment button click listener
        binding.btnAddPayment.setOnClickListener {
            val intent = Intent(this, AddPaymentActivity::class.java).apply {
                putExtra("PARTICIPANT_NAME", participantName)
                putExtra("EVENT_NAME", eventName) // pass event name
            }
            startActivityForResult(intent, ADD_PAYMENT_REQUEST)
        }
        binding.btnBackMain.setOnClickListener {
            finish()
        }
    }

    private fun loadPayments(eventName: String?, participantName: String) {
        eventName?.let {
            // load payments for the participant
            database.child(it).child("participants").child(participantName).addValueEventListener(object : ValueEventListener {
                override fun onDataChange(snapshot: DataSnapshot) {
                    paymentList.clear()
                    var totalAmount = 0.0 // initialize total amount
                    for (paymentSnapshot in snapshot.children) {
                        val payment = paymentSnapshot.getValue(Payment::class.java)
                        payment?.let {
                            paymentList.add(it) // add payment to the list
                            totalAmount += it.amount // sum amounts
                        }
                    }
                    paymentAdapter.notifyDataSetChanged() // notify adapter of data change
                    binding.tvParticipantAmount.text = "Total money spent: $totalAmount" // display total
                    Log.d(TAG, "Total payments loaded: ${paymentList.size}, Total amount: $totalAmount")
                }

                override fun onCancelled(error: DatabaseError) {
                }
            })
        }
    }

    // handle the result from AddPaymentActivity
    override fun onActivityResult(requestCode: Int, resultCode: Int, data: Intent?) {
        super.onActivityResult(requestCode, resultCode, data)
        if (requestCode == ADD_PAYMENT_REQUEST && resultCode == RESULT_OK) {
            // reload payments if added successfully
            Log.d(TAG, "Payment added, reloading payments")
            loadPayments(intent.getStringExtra("EVENT_NAME"), intent.getStringExtra("PARTICIPANT_NAME")!!)
        }
    }

    companion object {
        const val ADD_PAYMENT_REQUEST = 1
    }
}
