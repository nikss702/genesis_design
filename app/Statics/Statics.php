<?php

namespace App\Statics;

class Statics
{
//    Design request
    const DESIGN_STATUS_REQUESTED = "requested";
    const DESIGN_STATUS_IN_PROGRESS = "in progress";
    const DESIGN_STATUS_COMPLETED = "completed";

    const DESIGN_STATUSES = [self::DESIGN_STATUS_REQUESTED, self::DESIGN_STATUS_IN_PROGRESS, self::DESIGN_STATUS_COMPLETED];

    const DESIGN_TYPE_AURORA = "aurora design";
    const DESIGN_TYPE_STRUCTURAL = "structural load letter and calculations";
    const DESIGN_TYPE_PE = "pe stamping";
    const DESIGN_TYPE_ELECTRICAL = "electrical load calculations";
    const DESIGN_TYPE_SITE_SURVEY = "site survey";
    const DESIGN_TYPE_ENGINEERING_PERMIT = "engineering permit package";

    const DESIGN_TYPES = [self::DESIGN_TYPE_AURORA, self::DESIGN_TYPE_STRUCTURAL, self::DESIGN_TYPE_PE, self::DESIGN_TYPE_ELECTRICAL, self::DESIGN_TYPE_SITE_SURVEY, self::DESIGN_TYPE_ENGINEERING_PERMIT];

//    Equipment
    const EQUIPMENT_TYPE_OTHER = "other";
    const EQUIPMENT_TYPE_MONITOR = "monitor";
    const EQUIPMENT_TYPE_MODULE = "module";
    const EQUIPMENT_TYPE_INVERTER = "inverter";
    const EQUIPMENT_TYPE_RACKING = "racking";

    const EQUIPMENT_TYPES = [self::EQUIPMENT_TYPE_INVERTER, self::EQUIPMENT_TYPE_MODULE, self::EQUIPMENT_TYPE_RACKING, self::EQUIPMENT_TYPE_MONITOR, self::EQUIPMENT_TYPE_OTHER];

//    Project types
    const PROJECT_TYPE_RESIDENTIAL = "residential";
    const PROJECT_TYPE_COMMERCIAL = "commercial";

    const PROJECT_TYPES = [self::PROJECT_TYPE_RESIDENTIAL, self::PROJECT_TYPE_COMMERCIAL];

    //    Project statuses
    const PROJECT_STATUS_ASSIGNED = "assigned";
    const PROJECT_STATUS_NOT_ASSIGNED = "not assigned";
    const PROJECT_STATUS_ON_HOLD = "on hold";
    const PROJECT_STATUS_IN_PROCESS = "in process";
    const PROJECT_STATUS_ARCHIVED = "archived";
    const PROJECT_STATUS_COMPLETED = "completed";
    const PROJECT_STATUS_CANCELLED = "cancelled";

    const PROJECT_STATUSES = [self::PROJECT_STATUS_ASSIGNED, self::PROJECT_STATUS_NOT_ASSIGNED, self::PROJECT_STATUS_ON_HOLD, self::PROJECT_STATUS_IN_PROCESS, self::PROJECT_STATUS_ARCHIVED, self::PROJECT_STATUS_COMPLETED, self::PROJECT_STATUS_CANCELLED];

//    Status Types
    const STATUS_ACTIVE = "active";
    const STATUS_IN_ACTIVE = "in active";

    const STATUSES = [self::STATUS_ACTIVE, self::STATUS_IN_ACTIVE];

//    Design Status Customer
    const DESIGN_STATUS_CUSTOMER_HOLD = "on hold";
    const DESIGN_STATUS_CUSTOMER_CHANGE_REQUEST = "change request";
    const DESIGN_STATUS_CUSTOMER_COMPLETED = "completed";
    const DESIGN_STATUS_CUSTOMER_RECEIVED = "received";
    const DESIGN_STATUS_CUSTOMER_PROGRESS = "in progress";
    const DESIGN_STATUS_CUSTOMER_REQUESTED = "requested";

    const DESIGN_STATUS_CUSTOMER = [self::DESIGN_STATUS_CUSTOMER_HOLD, self::DESIGN_STATUS_CUSTOMER_CHANGE_REQUEST, self::DESIGN_STATUS_CUSTOMER_COMPLETED, self::DESIGN_STATUS_CUSTOMER_RECEIVED, self::DESIGN_STATUS_CUSTOMER_PROGRESS, self::DESIGN_STATUS_CUSTOMER_REQUESTED];

//    Design Status Customer
    const DESIGN_STATUS_ENGINEER_HOLD = "on hold";
    const DESIGN_STATUS_ENGINEER_COMPLETED = "completed";
    const DESIGN_STATUS_ENGINEER_SUMBITTED = "submitted";
    const DESIGN_STATUS_ENGINEER_PROGRESS = "in progress";
    const DESIGN_STATUS_ENGINEER_NOT_ASSIGNED = "not assigned";
    const DESIGN_STATUS_ENGINEER_REQUESTED = "requested";

    const DESIGN_STATUS_ENGINEER = [self::DESIGN_STATUS_ENGINEER_HOLD, self::DESIGN_STATUS_ENGINEER_COMPLETED, self::DESIGN_STATUS_ENGINEER_SUMBITTED, self::DESIGN_STATUS_ENGINEER_PROGRESS, self::DESIGN_STATUS_ENGINEER_NOT_ASSIGNED, self::DESIGN_STATUS_ENGINEER_REQUESTED];

//    ProjectFile categories
    const FILE_CATEGORY_ROOF = "roof";
    const FILE_CATEGORY_YARD = "yard";
    const FILE_CATEGORY_OTHER = "other";

    const FILE_CATEGORIES = [self::FILE_CATEGORY_ROOF, self::FILE_CATEGORY_YARD, self::FILE_CATEGORY_OTHER];

//    User Type
    const USER_TYPE_ADMIN = "admin";
    const USER_TYPE_CUSTOMER = "customer";
    const USER_TYPE_ENGINEER = "engineer";
    const USER_TYPE_MANAGER = "manager";

    const USER_TYPES = [self::USER_TYPE_ADMIN, self::USER_TYPE_CUSTOMER, self::USER_TYPE_ENGINEER,self::USER_TYPE_MANAGER];

//    Proposals File Type
    const PROPOSAL_FILE_TYPE_FULL = "full";
    const PROPOSAL_FILE_TYPE_PARTIAL = "partial";

    const PROPOSAL_FILE_TYPES = [self::PROPOSAL_FILE_TYPE_FULL, self::PROPOSAL_FILE_TYPE_PARTIAL];

    //    change request statues Type
    const CHANGE_REQUEST_STATUS_REQUESTED = "requested";
    const CHANGE_REQUEST_STATUS_AWAITING_APPROVAL = "awaiting approval";
    const CHANGE_REQUEST_STATUS_APPROVED = "approved";
    const CHANGE_REQUEST_STATUS_REJECTED = "rejected";
    const CHANGE_REQUEST_STATUS_CLOSED = "closed";


    const CHANGE_REQUEST_STATUSES = [self::CHANGE_REQUEST_STATUS_REQUESTED, self::CHANGE_REQUEST_STATUS_AWAITING_APPROVAL, self::CHANGE_REQUEST_STATUS_APPROVED, self::CHANGE_REQUEST_STATUS_REJECTED, self::CHANGE_REQUEST_STATUS_CLOSED];
}
