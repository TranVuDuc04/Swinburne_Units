package com.example.moneyshare.activity

import android.os.Bundle
import android.util.Log
import android.widget.Toast
import androidx.appcompat.app.AppCompatActivity
import com.example.moneyshare.data.Payment
import com.example.moneyshare.databinding.ActivityAddPaymentBinding
import com.google.firebase.database.DataSnapshot
import com.google.firebase.database.DatabaseError
import com.google.firebase.database.DatabaseReference
import com.google.firebase.database.FirebaseDatabase
import com.google.firebase.database.ValueEventListener


class AddPaymentActivity : AppCompatActivity() {

    private lateinit var binding: ActivityAddPaymentBinding
    private lateinit var database: DatabaseReference

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        binding = ActivityAddPaymentBinding.inflate(layoutInflater)
        setContentView(binding.root)

        // initialize firebase reference
        database = FirebaseDatabase.getInstance().getReference("Payments")

        // get event and participant info
        val eventName = intent.getStringExtra("EVENT_NAME") ?: ""
        val participantName = intent.getStringExtra("PARTICIPANT_NAME") ?: ""

        // save button click listener
        binding.btnSavePayment.setOnClickListener {
            val paymentAmount = binding.etPaymentAmount.text.toString().toDoubleOrNull()
            val paymentDescription = binding.etPaymentDescription.text.toString()

            if (isPaymentInputValid(paymentAmount, paymentDescription)) {
                addPaymentToFirebase(eventName, participantName, paymentAmount!!, paymentDescription)
            } else {
                Toast.makeText(this, "Please enter valid payment details", Toast.LENGTH_SHORT).show()
            }
        }
    }

    // validates payment input
    private fun isPaymentInputValid(amount: Double?, description: String): Boolean {
        return amount != null && description.isNotBlank()
    }

    // adds payment to firebase
    private fun addPaymentToFirebase(eventName: String, participantName: String, amount: Double, description: String) {
        val paymentData = Payment(amount, description)

        val participantRef = database.child(eventName).child("participants").child(participantName)

        // retrieve current payments
        participantRef.get().addOnSuccessListener { snapshot ->
            val paymentCount = snapshot.childrenCount.toInt()
            val newPaymentKey = "Payment ${paymentCount + 1}"

            // store new payment
            participantRef.child(newPaymentKey).setValue(paymentData)
                .addOnSuccessListener {
                    updateTotalAmount(eventName, participantName) // update total amount
                    setResult(RESULT_OK)
                    finish()
                }
                .addOnFailureListener {
                    Toast.makeText(this, "Failed to add payment.", Toast.LENGTH_SHORT).show()
                }
        }.addOnFailureListener {
            Toast.makeText(this, "Failed to retrieve payments.", Toast.LENGTH_SHORT).show()
        }
    }

    // updates total amount
    private fun updateTotalAmount(eventName: String, participantName: String) {
        val participantRef = FirebaseDatabase.getInstance().getReference("Participants").child(eventName).child(participantName)

        val paymentsRef = FirebaseDatabase.getInstance().getReference("Payments").child(eventName).child("participants").child(participantName)
        paymentsRef.addListenerForSingleValueEvent(object : ValueEventListener {
            override fun onDataChange(snapshot: DataSnapshot) {
                var totalAmount = 0.0 // initialize total amount
                for (paymentSnapshot in snapshot.children) {
                    val payment = paymentSnapshot.getValue(Payment::class.java)
                    totalAmount += payment?.amount ?: 0.0
                }
                // update total amount
                participantRef.child("totalAmount").setValue(totalAmount)
            }

            override fun onCancelled(error: DatabaseError) {
                Log.e("check", "Failed to calculate total amount: ${error.message}")
            }
        })
    }
}
