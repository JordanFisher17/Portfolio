package com.example.cs50project

import android.app.DownloadManager
import android.content.ContentValues
import android.content.Intent
import android.database.Cursor
import android.database.sqlite.SQLiteOpenHelper
import android.database.sqlite.SQLiteQuery
import androidx.appcompat.app.AppCompatActivity
import android.os.Bundle
import android.widget.*
import kotlinx.coroutines.newFixedThreadPoolContext
import org.w3c.dom.Text

class Index : AppCompatActivity() {
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_index)

        Toast.makeText(applicationContext, "Welcome to Secure Notes", Toast.LENGTH_SHORT).show()

        //Declare variables and database to be used in this activity

        val etusername = intent.getStringExtra("Username")

        // Add Database to Activity
        var users = dbHelper(applicationContext)
        var db = users.readableDatabase


        //Display Saved Text
        var display = findViewById(R.id.etNotes) as EditText
        val cursor = db.rawQuery("SELECT * FROM Users WHERE Username = ?", arrayOf(etusername))
        if (cursor.moveToFirst()) {
            do {
                var notes = cursor.getString(cursor.getColumnIndex(COL_NOTES))
                display.setText(notes.toString())


            } while (cursor.moveToNext())
        }

        //Program Log out button
        val btn_logout = findViewById(R.id.btn_logout) as Button
        btn_logout.setOnClickListener {
            val main = Intent(this, MainActivity::class.java)
            startActivity(main)
        }

        //identify Save Button

            val btn_save = findViewById(R.id.btnSave) as Button
            btn_save.setOnClickListener {

                var db = users.writableDatabase
                var notes = findViewById(R.id.etNotes) as EditText

                //Update Database via update function
                val username = etusername
                users.AddNotes(username.toString(), notes.text.toString())

                Toast.makeText(applicationContext, "Saved", Toast.LENGTH_SHORT).show()

            }
    }
}