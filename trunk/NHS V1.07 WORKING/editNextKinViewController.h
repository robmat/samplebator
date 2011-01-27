//
//  editNextKinViewController.h
//  PushViewControllerAnimated
//
//  Created by Andrew  Farmer on 26/03/2010.
//  Copyright 2010 Kioty Ltd. All rights reserved.
//

#import <UIKit/UIKit.h>
#import <AddressBook/AddressBook.h>
#import <AddressBookUI/AddressBookUI.h> 
#import "alarmSettingsNEW.h"
#import "PushViewControllerAnimatedAppDelegate.h"

@interface editNextKinViewController : UIViewController<ABPeoplePickerNavigationControllerDelegate> {
	
	ABPeoplePickerNavigationController *picker;
	IBOutlet UILabel *phoneNo;
	IBOutlet UILabel *email;
	IBOutlet UILabel *name;
}

-(IBAction)chooseContacts; 
-(IBAction)saveAction:(id)sender;
-(NSString *) dataFilePath ;

@end