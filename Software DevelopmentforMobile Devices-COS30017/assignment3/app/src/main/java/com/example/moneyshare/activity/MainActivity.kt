package com.example.moneyshare.activity

import android.content.Intent
import android.os.Bundle
import android.util.Log
import android.widget.Toast
import androidx.appcompat.app.AppCompatActivity
import androidx.recyclerview.widget.LinearLayoutManager
import com.example.moneyshare.adapter.EventAdapter
import com.example.moneyshare.data.EventData
import com.example.moneyshare.databinding.ActivityMainBinding
import com.google.firebase.database.*

class MainActivity : AppCompatActivity() {

    private val TAG = "MainActivity"
    private lateinit var binding: ActivityMainBinding
    private lateinit var database: DatabaseReference
    private lateinit var eventAdapter: EventAdapter
    val eventList = mutableListOf<EventData>()

    public override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        binding = ActivityMainBinding.inflate(layoutInflater)
        setContentView(binding.root)

        database = FirebaseDatabase.getInstance().getReference("Event Information") // firebase reference
        Log.d(TAG, "Database reference initialized")
        eventAdapter = EventAdapter(eventList) { event ->
            deleteEvent(event)
        }

        binding.rvEvent.layoutManager = LinearLayoutManager(this)
        binding.rvEvent.adapter = eventAdapter

        // retrieve data from firebase
        database.addValueEventListener(object : ValueEventListener {
            override fun onDataChange(snapshot: DataSnapshot) {
                eventList.clear()
                for (eventSnapshot in snapshot.children) {
                    val event = eventSnapshot.getValue(EventData::class.java)
                    if (event != null) {
                        eventList.add(event)
                    }
                }
                eventAdapter.notifyDataSetChanged()
                Log.d(TAG, "Event list updated, total events: ${eventList.size}")
            }

            override fun onCancelled(error: DatabaseError) {
                Toast.makeText(this@MainActivity, "Failed to load events: ${error.message}", Toast.LENGTH_SHORT).show()
            }
        })

        // button to add event
        binding.btnAddEvent.setOnClickListener {
            val intent = Intent(this@MainActivity, UploadActivity::class.java)
            startActivity(intent) // start UploadActivity
        }

        // button to navigate to FeedbackActivity
        binding.btnFeedback.setOnClickListener {
            val intent = Intent(this, FeedbackActivity::class.java)
            startActivity(intent) // start FeedbackActivity
        }
    }

    // delete event from database
    fun deleteEvent(event: EventData) {
        database.child(event.eventName ?: "").removeValue() // remove event
            .addOnSuccessListener {
                Toast.makeText(this, "Event deleted successfully", Toast.LENGTH_SHORT).show() // success message
            }
            .addOnFailureListener {
                Toast.makeText(this, "Failed to delete event", Toast.LENGTH_SHORT).show() // failure message
            }
    }
}
