package com.example.moneyshare.activity

import android.content.Intent
import android.util.Log
import android.os.Bundle
import android.widget.Toast
import androidx.appcompat.app.AppCompatActivity
import androidx.recyclerview.widget.LinearLayoutManager
import com.example.moneyshare.adapter.ParticipantAdapter
import com.example.moneyshare.data.ParticipantData
import com.example.moneyshare.databinding.ActivityParticipantBinding
import com.google.firebase.database.*

class ParticipantActivity : AppCompatActivity() {

    private lateinit var binding: ActivityParticipantBinding
    private lateinit var participantAdapter: ParticipantAdapter
    val participantList = mutableListOf<ParticipantData>()
    private lateinit var database: DatabaseReference
    private val TAG = "ParticipantActivity"
    public override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        binding = ActivityParticipantBinding.inflate(layoutInflater)
        setContentView(binding.root) // set content view

        val eventName = intent.getStringExtra("EVENT_NAME") ?: return // ensure eventName is not null
        Log.d(TAG, "Event Name: $eventName")
        binding.tvEventName.text = "Event: $eventName"
        binding.rvParticipants.layoutManager = LinearLayoutManager(this)
        participantAdapter = ParticipantAdapter(participantList) { participant ->
            // navigate to PaymentActivity on long click
            val intent = Intent(this, PaymentActivity::class.java).apply {
                putExtra("PARTICIPANT_NAME", participant.name)
                putExtra("EVENT_NAME", eventName)
            }
            startActivity(intent)
            Log.d(TAG, "Navigating to PaymentActivity for participant: ${participant.name}")
        }
        binding.rvParticipants.adapter = participantAdapter

        // setup firebase database reference
        database = FirebaseDatabase.getInstance().getReference("Participants").child(eventName)

        // load participants from firebase
        loadParticipants()

        binding.btnBackMain.setOnClickListener {
            finish() // finish activity
        }

        // add new participant
        binding.btnAddParticipant.setOnClickListener {
            val newParticipantName = "Participant ${participantList.size + 1}" // generate name
            val newParticipant = ParticipantData(name = newParticipantName) // create participant

            // store participant in database
            val newParticipantRef = database.child(newParticipantName)
            newParticipantRef.setValue(newParticipant).addOnCompleteListener { task ->
                if (task.isSuccessful) {
                    Toast.makeText(this, "Participant added", Toast.LENGTH_SHORT).show() // success message
                    loadParticipants() // reload participants
                } else {
                    Toast.makeText(this, "Failed to add participant", Toast.LENGTH_SHORT).show() // error message
                }
            }
        }
    }

    private fun loadParticipants() {
        database.addValueEventListener(object : ValueEventListener {
            override fun onDataChange(snapshot: DataSnapshot) {
                participantList.clear()
                var totalMoney = 0.0 // initialize total money
                val participantCount = snapshot.childrenCount.toInt() // get count

                for (participantSnapshot in snapshot.children) {
                    val participant = participantSnapshot.getValue(ParticipantData::class.java)
                    if (participant != null) {
                        participantList.add(participant) // add to list
                        totalMoney += participant.totalAmount // sum amounts
                        Log.d(TAG, "Participant added: ${participant.name}, Total Amount: ${participant.totalAmount}")
                    }
                }

                // calculate share per participant
                val sharePerParticipant = if (participantCount > 0) totalMoney / participantCount else 0.0

                // update each participant's amount owed
                participantList.forEach { participant ->
                    participant.amountOwed = sharePerParticipant - participant.totalAmount
                    database.child(participant.name ?: "unknown").setValue(participant).addOnCompleteListener { task ->
                        if (!task.isSuccessful) {
                            Toast.makeText(this@ParticipantActivity, "Failed to update ${participant.name}", Toast.LENGTH_SHORT).show() // update error
                        }
                    }
                }

                participantAdapter.notifyDataSetChanged() // notify adapter

                // update total participants and money
                binding.tvTotalParticipants.text = "Total Participants: ${participantList.size}"
                binding.tvTotalMoney.text = "Total Money: $${String.format("%.2f", totalMoney)}" // format currency
            }

            override fun onCancelled(error: DatabaseError) {
                Toast.makeText(this@ParticipantActivity, "Failed to load participants.", Toast.LENGTH_SHORT).show()
            }
        })
    }
}
