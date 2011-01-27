

#import <UIKit/UIKit.h>
#import "PushViewControllerAnimatedAppDelegate.h"

NSString * informacion ;

@interface hospitalMAPDetail : UIViewController {

	IBOutlet UILabel * labelNombre ;
	IBOutlet UILabel * labelPartner ;
	IBOutlet UILabel * labelManagerName ;
	IBOutlet UITextView * labelManagerMail ;
	IBOutlet UILabel * labelAddress1 ;
	IBOutlet UILabel * labelAddress2 ;
	
	IBOutlet UITextView * labelPhone ;
	
	IBOutlet UILabel * labelAddress1b ;
	
}

-(NSString *) dataFilePathHospital ;

-(IBAction)makeAppointmentButton ;
-(IBAction)sendMail:(id)sender ;

@end