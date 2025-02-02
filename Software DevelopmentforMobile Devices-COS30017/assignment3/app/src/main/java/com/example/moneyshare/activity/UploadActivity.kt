package com.example.moneyshare.activity

import android.content.Intent
import android.os.Bundle
import android.widget.Toast
import androidx.appcompat.app.AppCompatActivity
import com.example.moneyshare.data.EventData
import com.example.moneyshare.databinding.ActivityUploadBinding
import com.google.firebase.database.DatabaseReference
import com.google.firebase.database.FirebaseDatabase

class UploadActivity : AppCompatActivity() {

    private lateinit var binding: ActivityUploadBinding
    private lateinit var databaseReference: DatabaseReference

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        binding = ActivityUploadBinding.inflate(layoutInflater)
        setContentView(binding.root) // set content view

        // initialize firebase database reference
        databaseReference = FirebaseDatabase.getInstance().getReference("Event Information")

        binding.btnCreate.setOnClickListener {
            val eventName = binding.etName.text.toString().trim() // get event name
            val eventDate = binding.etDate.text.toString().trim() // get event date

            if (eventName.isNotEmpty() && eventDate.isNotEmpty()) {
                val eventData = EventData(eventName, eventDate) // create event data

                // save event data to firebase
                databaseReference.child(eventName).setValue(eventData)
                    .addOnSuccessListener {
                        binding.etName.text.clear() // clear input fields
                        binding.etDate.text.clear()

                        Toast.makeText(this, "Saved Successfully", Toast.LENGTH_SHORT).show() // show success message
                        val intent = Intent(this, MainActivity::class.java) // navigate to main activity
                        startActivity(intent)
                        finish() // finish current activity
                    }.addOnFailureListener {
                        Toast.makeText(this, "Failed to save data", Toast.LENGTH_SHORT).show() // show failure message
                    }
            } else {
                Toast.makeText(this, "Please enter both name and date", Toast.LENGTH_SHORT).show() // prompt for input
            }
        }
    }
}
