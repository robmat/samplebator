//
//  StopSmokingDetailVC.h
//  PushViewControllerAnimated
//
//  Created by User on 4/29/11.
//  Copyright 2011 __MyCompanyName__. All rights reserved.
//

#import <UIKit/UIKit.h>


@interface StopSmokingDetailVC : UIViewController {
	
	IBOutlet UILabel * labelNombre ;
	IBOutlet UILabel * labelPartner ;
	IBOutlet UILabel * labelManagerName ;
	IBOutlet UITextView * labelManagerMail ;
	IBOutlet UILabel * labelAddress1 ;
	IBOutlet UILabel * labelAddress2 ;
	IBOutlet UILabel * comment ;
	
	IBOutlet UITextView * labelPhone ;
	
	IBOutlet UILabel * labelAddress1b;
	NSString* openingTimes;
}

@property (nonatomic, copy) NSString* openingTimes;

-(NSString *) dataFilePathSex ;
//-(IBAction)makeAppointmentButton ;
-(IBAction)sendMail:(id)sender ;
-(IBAction)showOpeningTimes:(id)sender;

@end
