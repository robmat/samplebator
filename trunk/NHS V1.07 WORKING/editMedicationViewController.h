//
//  editMedicationViewController.h
//  PushViewControllerAnimated
//
//  Created by Andrew  Farmer on 26/03/2010.
//  Copyright 2010 Kioty Ltd. All rights reserved.
//

#import <UIKit/UIKit.h>


@interface editMedicationViewController : UIViewController {

	IBOutlet UITextField * nameField ;
	IBOutlet UITextView * medicationField ;

}


-(IBAction)saveAction:(id)sender ;


-(NSString *) dataFilePath ;

@end
