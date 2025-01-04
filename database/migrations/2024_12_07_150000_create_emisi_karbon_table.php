<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            CREATE TABLE emisi_carbons (
                id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                kode_emisi_karbon VARCHAR(255) UNIQUE,
                kategori_emisi_karbon VARCHAR(255),
                sub_kategori VARCHAR(255),
                nilai_aktivitas DECIMAL(10,2),
                faktor_emisi DECIMAL(10,2),
                kadar_emisi_karbon DECIMAL(10,2) GENERATED ALWAYS AS (nilai_aktivitas * faktor_emisi) STORED,
                deskripsi VARCHAR(255),
                status VARCHAR(255),
                kode_manager VARCHAR(255),
                kode_user VARCHAR(255),
                kode_admin VARCHAR(255),
                tanggal_emisi DATE,
                created_at TIMESTAMP NULL,
                updated_at TIMESTAMP NULL,
                FOREIGN KEY (kode_manager) REFERENCES managers(kode_manager) ON DELETE CASCADE,
                FOREIGN KEY (kode_user) REFERENCES penggunas(kode_user) ON DELETE CASCADE,
                FOREIGN KEY (kode_admin) REFERENCES admins(kode_admin) ON DELETE CASCADE,
                FOREIGN KEY (kategori_emisi_karbon, sub_kategori) 
                    REFERENCES faktor_emisis(kategori_emisi_karbon, sub_kategori)
            )
        ");

        
        DB::unprepared("
            CREATE TRIGGER set_faktor_emisi_before_insert 
            BEFORE INSERT ON emisi_carbons
            FOR EACH ROW
            BEGIN
                SET NEW.faktor_emisi = (
                    SELECT nilai_faktor
                    FROM faktor_emisis
                    WHERE kategori_emisi_karbon = NEW.kategori_emisi_karbon
                    AND sub_kategori = NEW.sub_kategori
                    LIMIT 1
                );
            END
        ");

        DB::unprepared("
            CREATE TRIGGER set_faktor_emisi_before_update
            BEFORE UPDATE ON emisi_carbons
            FOR EACH ROW
            BEGIN
                IF (NEW.kategori_emisi_karbon != OLD.kategori_emisi_karbon OR NEW.sub_kategori != OLD.sub_kategori) THEN
                    SET NEW.faktor_emisi = (
                        SELECT nilai_faktor
                        FROM faktor_emisis
                        WHERE kategori_emisi_karbon = NEW.kategori_emisi_karbon
                        AND sub_kategori = NEW.sub_kategori
                        LIMIT 1
                    );
                END IF;
            END
        ");
    }

    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS set_faktor_emisi_before_insert');
        DB::unprepared('DROP TRIGGER IF EXISTS set_faktor_emisi_before_update');
        DB::statement('DROP TABLE IF EXISTS emisi_carbons');
    }
};
