package com.example.moneyshare.activity

import android.annotation.SuppressLint
import android.content.Intent
import android.os.Bundle
import android.util.Log
import android.widget.Toast
import androidx.appcompat.app.AppCompatActivity
import androidx.recyclerview.widget.LinearLayoutManager
import androidx.recyclerview.widget.RecyclerView
import com.example.moneyshare.adapter.EventAdapter
import com.example.moneyshare.data.EventData
import com.example.moneyshare.databinding.ActivityMainBinding
import com.google.firebase.database.*

class MainActivity : AppCompatActivity() {

    private val TAG = "MainActivity"
    private lateinit var binding: ActivityMainBinding
    private lateinit var database: DatabaseReference
    private lateinit var eventAdapter: EventAdapter
    private var lastVisible : DataSnapshot? = null
    val eventList = mutableListOf<EventData>()
    val eventNum = 3

    public override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        binding = ActivityMainBinding.inflate(layoutInflater)
        setContentView(binding.root)

        database = FirebaseDatabase.getInstance().getReference("Event Information")
        eventAdapter = EventAdapter(eventList) { event ->
            deleteEvent(event)
        }
        binding.rvEvent.layoutManager = LinearLayoutManager(this)
        binding.rvEvent.adapter = eventAdapter

        // load initial data
        loadParticipants()

        // setup scroll listener for pagination
        binding.rvEvent.addOnScrollListener(object : RecyclerView.OnScrollListener() {
            override fun onScrolled(recyclerView: RecyclerView, dx: Int, dy: Int) {
                val layoutManager = recyclerView.layoutManager as LinearLayoutManager
                if (layoutManager.findLastCompletelyVisibleItemPosition() == eventList.size - 1) {
                    loadMoreParticipants()
                }
            }
        })

        // button to add event
        binding.btnAddEvent.setOnClickListener {
            val intent = Intent(this@MainActivity, UploadActivity::class.java)
            startActivity(intent)
        }

        // button to navigate to FeedbackActivity
        binding.btnFeedback.setOnClickListener {
            val intent = Intent(this, FeedbackActivity::class.java)
            startActivity(intent)
        }
    }

    // function to load participants (refactored)
    private fun loadParticipants() {
        val query = database
            .orderByKey()
            .limitToFirst(eventNum)

        query.addListenerForSingleValueEvent(object : ValueEventListener {
            override fun onDataChange(snapshot: DataSnapshot) {
                if (snapshot.exists()) {
                    eventList.clear() // clear the list before adding new data
                    for (eventSnapshot in snapshot.children) {
                        val event = eventSnapshot.getValue(EventData::class.java)
                        event?.let { eventList.add(it) }
                    }
                    lastVisible = snapshot.children.lastOrNull()
                    eventAdapter.notifyDataSetChanged()
                }
            }

            override fun onCancelled(error: DatabaseError) {
                Toast.makeText(this@MainActivity, "Failed to load events: ${error.message}", Toast.LENGTH_SHORT).show()
            }
        })
    }

    // function to load more participants (refactored)
    private fun loadMoreParticipants() {
        if (lastVisible != null) {
            val query = database
                .orderByKey()
                .startAfter(lastVisible!!.key)
                .limitToFirst(eventNum)

            query.addListenerForSingleValueEvent(object : ValueEventListener {
                override fun onDataChange(snapshot: DataSnapshot) {
                    if (snapshot.exists()) {
                        for (eventSnapshot in snapshot.children) {
                            val event = eventSnapshot.getValue(EventData::class.java)
                            event?.let { eventList.add(it) }
                        }
                        lastVisible = snapshot.children.lastOrNull()
                        eventAdapter.notifyDataSetChanged()
                    }
                }

                override fun onCancelled(error: DatabaseError) {
                    // handle errors
                }
            })
        }
    }

    // delete event from database
    fun deleteEvent(event: EventData) {
        database.child(event.eventName ?: "").removeValue()
            .addOnSuccessListener {
                eventList.remove(event)
                eventAdapter.notifyDataSetChanged()
                Toast.makeText(this, "Event deleted successfully", Toast.LENGTH_SHORT).show()
            }
            .addOnFailureListener {
                Toast.makeText(this, "Failed to delete event", Toast.LENGTH_SHORT).show()
            }
    }
}

